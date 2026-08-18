"use client";

import Link from "next/link";
import { useLanguage } from "@/lib/language-context";

export function Footer() {
  const { dict } = useLanguage();

  return (
    <footer className="border-t border-border-soft bg-surface/40 pb-24 md:pb-10">
      <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div className="flex flex-col gap-8 md:flex-row md:items-start md:justify-between">
          <div>
            <span className="text-lg font-semibold tracking-[0.2em] text-ivory">ATEN</span>
            <p className="mt-2 max-w-sm text-sm text-ivory-muted">{dict.footer.tagline}</p>
          </div>

          <div className="flex gap-12 text-sm">
            <div className="flex flex-col gap-2">
              <span className="text-xs font-medium uppercase tracking-wider text-ivory-muted">
                {dict.nav.products}
              </span>
              <Link href="/products" className="text-ivory-muted hover:text-ivory">
                {dict.nav.products}
              </Link>
              <Link href="/rfq" className="text-ivory-muted hover:text-ivory">
                {dict.nav.rfq}
              </Link>
              <Link href="/rfq/track" className="text-ivory-muted hover:text-ivory">
                {dict.rfq.trackTitle}
              </Link>
            </div>
            <div className="flex flex-col gap-2">
              <span className="text-xs font-medium uppercase tracking-wider text-ivory-muted">
                {dict.nav.about}
              </span>
              <Link href="/about" className="text-ivory-muted hover:text-ivory">
                {dict.nav.about}
              </Link>
              <Link href="/contact" className="text-ivory-muted hover:text-ivory">
                {dict.nav.contact}
              </Link>
            </div>
          </div>
        </div>

        <div className="mt-10 border-t border-border-soft pt-6 text-xs text-ivory-muted">
          © {new Date().getFullYear()} ATEN Industrial Connections. {dict.footer.rights}
        </div>
      </div>
    </footer>
  );
}
