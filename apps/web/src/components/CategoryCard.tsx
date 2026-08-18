"use client";

import Link from "next/link";
import { SmartImage } from "@/components/SmartImage";
import { productImageUrl } from "@/lib/api";
import { useLanguage } from "@/lib/language-context";
import type { Category } from "@/lib/types";

export function CategoryCard({ category }: { category: Category }) {
  const { lang } = useLanguage();

  return (
    <Link
      href={`/products?category=${category.slug}`}
      className="focus-ring group relative flex h-48 items-end overflow-hidden rounded-2xl border border-border-soft bg-surface"
    >
      <SmartImage
        src={productImageUrl(category.image)}
        alt={lang === "fa" ? category.name.fa : category.name.en}
        fill
        sizes="(min-width: 1024px) 25vw, (min-width: 640px) 33vw, 50vw"
        className="object-cover opacity-70 transition-transform duration-500 group-hover:scale-105"
      />
      <div className="absolute inset-0 bg-gradient-to-t from-ink via-ink/40 to-transparent" />
      <div className="relative z-10 p-4">
        <h3 className="text-sm font-medium text-ivory">
          {lang === "fa" ? category.name.fa : category.name.en}
        </h3>
        {typeof category.product_count === "number" && (
          <span className="text-xs text-ivory-muted">
            {category.product_count} {lang === "fa" ? "مورد" : "items"}
          </span>
        )}
      </div>
    </Link>
  );
}
