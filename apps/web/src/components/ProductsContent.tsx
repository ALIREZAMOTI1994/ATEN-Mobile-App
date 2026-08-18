"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";
import { ProductCard } from "@/components/ProductCard";
import { useLanguage } from "@/lib/language-context";
import type { Category, Industry, ProductListItem } from "@/lib/types";

type Filters = {
  category?: string;
  industry?: string;
  q?: string;
  food_grade?: string;
  medical_grade?: string;
  availability?: string;
  page?: string;
};

export function ProductsContent({
  products,
  meta,
  categories,
  industries,
  filters,
}: {
  products: ProductListItem[];
  meta: { current_page: number; last_page: number; total: number };
  categories: Category[];
  industries: Industry[];
  filters: Filters;
}) {
  const { lang, dict } = useLanguage();
  const router = useRouter();
  const [search, setSearch] = useState(filters.q ?? "");

  function updateParams(next: Partial<Filters>) {
    const merged = { ...filters, ...next, page: undefined };
    const params = new URLSearchParams();
    Object.entries(merged).forEach(([key, value]) => {
      if (value) params.set(key, String(value));
    });
    router.push(`/products${params.toString() ? `?${params.toString()}` : ""}`);
  }

  function goToPage(page: number) {
    const params = new URLSearchParams();
    Object.entries(filters).forEach(([key, value]) => {
      if (value) params.set(key, String(value));
    });
    params.set("page", String(page));
    router.push(`/products?${params.toString()}`);
  }

  return (
    <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
      <div className="grid gap-8 lg:grid-cols-[240px_1fr]">
        <aside className="flex flex-col gap-6">
          <form
            onSubmit={(e) => {
              e.preventDefault();
              updateParams({ q: search });
            }}
          >
            <input
              type="search"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder={dict.filters.search}
              className="focus-ring w-full rounded-lg border border-border bg-surface px-3 py-2.5 text-sm text-ivory placeholder:text-ivory-muted"
            />
          </form>

          <div>
            <span className="mb-2 block text-xs font-medium uppercase tracking-wider text-ivory-muted">
              {dict.filters.category}
            </span>
            <div className="flex flex-col gap-1">
              <button
                type="button"
                onClick={() => updateParams({ category: undefined })}
                className={`focus-ring rounded px-2 py-1.5 text-start text-sm ${
                  !filters.category ? "bg-surface text-ivory" : "text-ivory-muted hover:text-ivory"
                }`}
              >
                {dict.filters.clear}
              </button>
              {categories.map((cat) => (
                <button
                  key={cat.slug}
                  type="button"
                  onClick={() => updateParams({ category: cat.slug })}
                  className={`focus-ring rounded px-2 py-1.5 text-start text-sm ${
                    filters.category === cat.slug
                      ? "bg-surface text-ivory"
                      : "text-ivory-muted hover:text-ivory"
                  }`}
                >
                  {lang === "fa" ? cat.name.fa : cat.name.en}
                </button>
              ))}
            </div>
          </div>

          <div>
            <span className="mb-2 block text-xs font-medium uppercase tracking-wider text-ivory-muted">
              {dict.filters.industry}
            </span>
            <select
              value={filters.industry ?? ""}
              onChange={(e) => updateParams({ industry: e.target.value || undefined })}
              className="focus-ring w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-ivory"
            >
              <option value="">{dict.filters.clear}</option>
              {industries.map((ind) => (
                <option key={ind.slug} value={ind.slug}>
                  {lang === "fa" ? ind.name.fa : ind.name.en}
                </option>
              ))}
            </select>
          </div>

          <div className="flex flex-col gap-2 text-sm">
            <label className="flex items-center gap-2 text-ivory-muted">
              <input
                type="checkbox"
                checked={filters.food_grade === "1"}
                onChange={(e) => updateParams({ food_grade: e.target.checked ? "1" : undefined })}
                className="accent-(--color-accent)"
              />
              {dict.filters.foodGrade}
            </label>
            <label className="flex items-center gap-2 text-ivory-muted">
              <input
                type="checkbox"
                checked={filters.medical_grade === "1"}
                onChange={(e) => updateParams({ medical_grade: e.target.checked ? "1" : undefined })}
                className="accent-(--color-accent)"
              />
              {dict.filters.medicalGrade}
            </label>
          </div>
        </aside>

        <div>
          <div className="mb-4 text-sm text-ivory-muted">
            {meta.total} {dict.filters.results}
          </div>

          {products.length === 0 ? (
            <p className="py-16 text-center text-sm text-ivory-muted">No products match your filters.</p>
          ) : (
            <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
              {products.map((product) => (
                <ProductCard key={product.slug} product={product} />
              ))}
            </div>
          )}

          {meta.last_page > 1 && (
            <div className="mt-8 flex items-center justify-center gap-2">
              {Array.from({ length: meta.last_page }, (_, i) => i + 1).map((page) => (
                <button
                  key={page}
                  type="button"
                  onClick={() => goToPage(page)}
                  className={`focus-ring h-9 w-9 rounded-full text-sm ${
                    page === meta.current_page
                      ? "bg-accent text-ink"
                      : "text-ivory-muted hover:bg-surface"
                  }`}
                >
                  {page}
                </button>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
