"use client";

import Link from "next/link";
import { CategoryCard } from "@/components/CategoryCard";
import { ProductCard } from "@/components/ProductCard";
import { useLanguage } from "@/lib/language-context";
import type { Category, ProductListItem } from "@/lib/types";

export function HomeContent({
  categories,
  featured,
}: {
  categories: Category[];
  featured: ProductListItem[];
}) {
  const { dict } = useLanguage();

  return (
    <>
      <section className="border-b border-border-soft bg-gradient-to-b from-surface/60 to-ink">
        <div className="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
          <span className="text-xs font-medium uppercase tracking-[0.3em] text-accent">
            {dict.home.heroKicker}
          </span>
          <h1 className="mt-4 max-w-2xl text-3xl font-semibold leading-tight text-ivory sm:text-4xl lg:text-5xl">
            {dict.home.heroTitle}
          </h1>
          <p className="mt-5 max-w-xl text-base text-ivory-muted">{dict.home.heroSubtitle}</p>
          <div className="mt-8 flex flex-wrap gap-3">
            <Link
              href="/products"
              className="focus-ring rounded-full bg-accent px-6 py-3 text-sm font-medium text-ink transition-colors hover:bg-accent-strong"
            >
              {dict.home.browseCatalog}
            </Link>
            <Link
              href="/rfq"
              className="focus-ring rounded-full border border-border px-6 py-3 text-sm font-medium text-ivory transition-colors hover:border-accent"
            >
              {dict.home.requestQuote}
            </Link>
          </div>
        </div>
      </section>

      <section className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div className="mb-6 flex items-center justify-between">
          <h2 className="text-lg font-semibold text-ivory">{dict.home.categoriesTitle}</h2>
        </div>
        <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
          {categories.map((category) => (
            <CategoryCard key={category.slug} category={category} />
          ))}
        </div>
      </section>

      {featured.length > 0 && (
        <section className="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
          <div className="mb-6 flex items-center justify-between">
            <h2 className="text-lg font-semibold text-ivory">{dict.home.featuredTitle}</h2>
            <Link href="/products" className="text-sm text-accent hover:underline">
              {dict.home.viewAll}
            </Link>
          </div>
          <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            {featured.map((product) => (
              <ProductCard key={product.slug} product={product} />
            ))}
          </div>
        </section>
      )}
    </>
  );
}
