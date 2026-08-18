"use client";

import Link from "next/link";
import { SmartImage } from "@/components/SmartImage";
import { productImageUrl } from "@/lib/api";
import { useLanguage } from "@/lib/language-context";
import type { ProductListItem } from "@/lib/types";

export function ProductCard({ product }: { product: ProductListItem }) {
  const { lang } = useLanguage();

  return (
    <Link
      href={`/products/${product.slug}`}
      className="focus-ring group flex flex-col overflow-hidden rounded-2xl border border-border-soft bg-surface transition-colors hover:border-accent/60"
    >
      <div className="relative aspect-square w-full overflow-hidden bg-ink">
        <SmartImage
          src={productImageUrl(product.thumbnail)}
          alt={lang === "fa" ? product.name.fa : product.name.en}
          fill
          sizes="(min-width: 1024px) 25vw, (min-width: 640px) 33vw, 50vw"
          className="object-cover transition-transform duration-500 group-hover:scale-105"
        />
        {product.featured && (
          <span className="absolute start-2 top-2 rounded-full bg-accent px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wider text-ink">
            {lang === "fa" ? "ویژه" : "Featured"}
          </span>
        )}
      </div>
      <div className="flex flex-1 flex-col gap-1.5 p-4">
        <span className="text-[11px] uppercase tracking-wider text-ivory-muted">
          {product.category ? (lang === "fa" ? product.category.name.fa : product.category.name.en) : product.material}
        </span>
        <h3 className="text-sm font-medium text-ivory">
          {lang === "fa" ? product.name.fa : product.name.en}
        </h3>
        <p className="line-clamp-2 text-xs text-ivory-muted">{product.summary}</p>
        <div className="mt-auto flex items-center justify-between pt-3 text-xs">
          <span className="text-ivory-muted">{product.size_range ?? "—"}</span>
          <span
            className={
              product.availability === "In stock"
                ? "text-accent"
                : "text-ivory-muted"
            }
          >
            {product.availability}
          </span>
        </div>
      </div>
    </Link>
  );
}
