"use client";

import Link from "next/link";
import { useLanguage } from "@/lib/language-context";
import { useRfqCart } from "@/lib/rfq-cart";

export function RfqBar() {
  const { count } = useRfqCart();
  const { dict } = useLanguage();

  if (count === 0) return null;

  return (
    <div className="fixed inset-x-0 bottom-0 z-40 border-t border-border-soft bg-surface/95 backdrop-blur md:hidden">
      <Link
        href="/rfq"
        className="focus-ring flex items-center justify-between px-4 py-3.5 text-sm font-medium text-ivory"
      >
        <span className="flex items-center gap-2">
          <span className="flex h-6 w-6 items-center justify-center rounded-full bg-accent text-xs font-semibold text-ink">
            {count}
          </span>
          {dict.rfq.barLabel}
        </span>
        <span className="rounded-full bg-accent px-4 py-1.5 text-ink">{dict.rfq.viewRfq}</span>
      </Link>
    </div>
  );
}
