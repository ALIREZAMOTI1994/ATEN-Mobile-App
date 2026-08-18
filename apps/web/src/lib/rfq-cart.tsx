"use client";

import { createContext, useCallback, useContext, useEffect, useMemo, useState } from "react";

export type RfqCartItem = {
  productSlug: string;
  name: string;
  sku: string;
  thumbnail: string | null;
  quantity: number;
  unit: string;
  size?: string;
};

type RfqCartContextValue = {
  items: RfqCartItem[];
  add: (item: Omit<RfqCartItem, "quantity"> & { quantity?: number }) => void;
  remove: (productSlug: string) => void;
  updateQuantity: (productSlug: string, quantity: number) => void;
  clear: () => void;
  count: number;
};

const RfqCartContext = createContext<RfqCartContextValue | null>(null);
const STORAGE_KEY = "aten:rfq-cart";

export function RfqCartProvider({ children }: { children: React.ReactNode }) {
  const [items, setItems] = useState<RfqCartItem[]>([]);
  const [hydrated, setHydrated] = useState(false);

  useEffect(() => {
    // Read the persisted cart after mount so it matches the empty
    // server-rendered markup on first paint, avoiding a hydration
    // mismatch; this is the one-time exception to "no setState in effects".
    try {
      const raw = window.localStorage.getItem(STORAGE_KEY);
      // eslint-disable-next-line react-hooks/set-state-in-effect
      if (raw) setItems(JSON.parse(raw));
    } catch {
      // ignore malformed local storage
    }
    setHydrated(true);
  }, []);

  useEffect(() => {
    if (!hydrated) return;
    window.localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
  }, [items, hydrated]);

  const add = useCallback((item: Omit<RfqCartItem, "quantity"> & { quantity?: number }) => {
    setItems((prev) => {
      const existing = prev.find((i) => i.productSlug === item.productSlug);
      if (existing) {
        return prev.map((i) =>
          i.productSlug === item.productSlug
            ? { ...i, quantity: i.quantity + (item.quantity ?? 1) }
            : i,
        );
      }
      return [...prev, { ...item, quantity: item.quantity ?? 1 }];
    });
  }, []);

  const remove = useCallback((productSlug: string) => {
    setItems((prev) => prev.filter((i) => i.productSlug !== productSlug));
  }, []);

  const updateQuantity = useCallback((productSlug: string, quantity: number) => {
    setItems((prev) =>
      prev.map((i) => (i.productSlug === productSlug ? { ...i, quantity: Math.max(1, quantity) } : i)),
    );
  }, []);

  const clear = useCallback(() => setItems([]), []);

  const value = useMemo(
    () => ({ items, add, remove, updateQuantity, clear, count: items.length }),
    [items, add, remove, updateQuantity, clear],
  );

  return <RfqCartContext.Provider value={value}>{children}</RfqCartContext.Provider>;
}

export function useRfqCart() {
  const ctx = useContext(RfqCartContext);
  if (!ctx) throw new Error("useRfqCart must be used within RfqCartProvider");
  return ctx;
}
