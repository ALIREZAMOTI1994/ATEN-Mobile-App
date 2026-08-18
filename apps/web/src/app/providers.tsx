"use client";

import { LanguageProvider } from "@/lib/language-context";
import { RfqCartProvider } from "@/lib/rfq-cart";

export function Providers({ children }: { children: React.ReactNode }) {
  return (
    <LanguageProvider>
      <RfqCartProvider>{children}</RfqCartProvider>
    </LanguageProvider>
  );
}
