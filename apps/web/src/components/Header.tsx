"use client";

import Link from "next/link";
import { useState } from "react";
import { useLanguage } from "@/lib/language-context";
import { useRfqCart } from "@/lib/rfq-cart";

export function Header() {
  const { lang, setLang, dict } = useLanguage();
  const { count } = useRfqCart();
  const [open, setOpen] = useState(false);

  const links = [
    { href: "/", label: dict.nav.home },
    { href: "/products", label: dict.nav.products },
    { href: "/about", label: dict.nav.about },
    { href: "/contact", label: dict.nav.contact },
  ];

  return (
    <header className="sticky top-0 z-40 border-b border-border-soft bg-ink/90 backdrop-blur supports-[backdrop-filter]:bg-ink/70">
      <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <Link href="/" className="focus-ring flex items-center gap-2 rounded">
          <span className="text-xl font-semibold tracking-[0.2em] text-ivory">ATEN</span>
          <span className="hidden text-xs text-ivory-muted sm:inline">Industrial Connections</span>
        </Link>

        <nav className="hidden items-center gap-8 md:flex">
          {links.map((link) => (
            <Link
              key={link.href}
              href={link.href}
              className="focus-ring rounded text-sm text-ivory-muted transition-colors hover:text-ivory"
            >
              {link.label}
            </Link>
          ))}
        </nav>

        <div className="flex items-center gap-3">
          <button
            type="button"
            onClick={() => setLang(lang === "en" ? "fa" : "en")}
            className="focus-ring rounded border border-border px-2.5 py-1 text-xs font-medium text-ivory-muted transition-colors hover:border-accent hover:text-ivory"
            aria-label="Toggle language"
          >
            {lang === "en" ? "فا" : "EN"}
          </button>

          <Link
            href="/rfq"
            className="focus-ring relative flex items-center gap-2 rounded-full bg-accent px-4 py-2 text-sm font-medium text-ink transition-colors hover:bg-accent-strong"
          >
            {dict.nav.rfq}
            {count > 0 && (
              <span className="absolute -end-1.5 -top-1.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-ink px-1 text-[11px] font-semibold text-accent">
                {count}
              </span>
            )}
          </Link>

          <button
            type="button"
            className="focus-ring rounded p-2 text-ivory md:hidden"
            onClick={() => setOpen((v) => !v)}
            aria-label="Toggle menu"
            aria-expanded={open}
          >
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8">
              {open ? (
                <path d="M6 6l12 12M18 6L6 18" strokeLinecap="round" />
              ) : (
                <path d="M3 6h18M3 12h18M3 18h18" strokeLinecap="round" />
              )}
            </svg>
          </button>
        </div>
      </div>

      {open && (
        <nav className="border-t border-border-soft px-4 py-3 md:hidden">
          <ul className="flex flex-col gap-1">
            {links.map((link) => (
              <li key={link.href}>
                <Link
                  href={link.href}
                  onClick={() => setOpen(false)}
                  className="block rounded px-2 py-2.5 text-sm text-ivory-muted hover:bg-surface hover:text-ivory"
                >
                  {link.label}
                </Link>
              </li>
            ))}
          </ul>
        </nav>
      )}
    </header>
  );
}
