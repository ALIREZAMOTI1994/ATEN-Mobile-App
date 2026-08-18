"use client";

import { useState } from "react";
import { SmartImage } from "@/components/SmartImage";
import { productImageUrl, qrCodeUrl } from "@/lib/api";
import { useLanguage } from "@/lib/language-context";
import { useRfqCart } from "@/lib/rfq-cart";
import type { Product } from "@/lib/types";
import { SpecTable } from "@/components/SpecTable";
import { ProductCard } from "@/components/ProductCard";

export function ProductDetailContent({ product }: { product: Product }) {
  const { lang, dict } = useLanguage();
  const { add, items } = useRfqCart();
  const [activeImage, setActiveImage] = useState(0);
  const [justAdded, setJustAdded] = useState(false);

  const name = lang === "fa" ? product.name.fa : product.name.en;
  const images = product.images.length > 0 ? product.images : [null];
  const alreadyAdded = items.some((i) => i.productSlug === product.slug);

  function handleAdd() {
    add({
      productSlug: product.slug,
      name,
      sku: product.sku,
      thumbnail: product.images[0] ?? null,
      unit: "pcs",
    });
    setJustAdded(true);
    setTimeout(() => setJustAdded(false), 2000);
  }

  return (
    <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
      <div className="grid gap-10 lg:grid-cols-2">
        <div>
          <div className="relative aspect-square w-full overflow-hidden rounded-2xl border border-border-soft bg-surface">
            <SmartImage
              src={productImageUrl(images[activeImage])}
              alt={name}
              fill
              sizes="(min-width: 1024px) 50vw, 100vw"
              className="object-cover"
              priority
            />
          </div>
          {images.length > 1 && (
            <div className="mt-3 flex gap-2">
              {images.map((img, i) => (
                <button
                  key={i}
                  type="button"
                  onClick={() => setActiveImage(i)}
                  className={`focus-ring relative h-16 w-16 overflow-hidden rounded-lg border ${
                    activeImage === i ? "border-accent" : "border-border-soft"
                  }`}
                >
                  <SmartImage src={productImageUrl(img)} alt="" fill sizes="64px" className="object-cover" />
                </button>
              ))}
            </div>
          )}
        </div>

        <div>
          <span className="text-xs uppercase tracking-wider text-ivory-muted">
            {product.category ? (lang === "fa" ? product.category.name.fa : product.category.name.en) : ""}
          </span>
          <h1 className="mt-2 text-2xl font-semibold text-ivory sm:text-3xl">{name}</h1>
          <p className="mt-3 text-sm text-ivory-muted">
            {product.description ?? product.summary}
          </p>

          <dl className="mt-6 grid grid-cols-2 gap-4 border-y border-border-soft py-5 text-sm">
            <div>
              <dt className="text-ivory-muted">{dict.product.material}</dt>
              <dd className="mt-0.5 text-ivory">{product.material}</dd>
            </div>
            <div>
              <dt className="text-ivory-muted">{dict.product.sku}</dt>
              <dd className="mt-0.5 text-ivory">{product.sku}</dd>
            </div>
            {product.size_range && (
              <div>
                <dt className="text-ivory-muted">{dict.product.sizeRange}</dt>
                <dd className="mt-0.5 text-ivory">{product.size_range}</dd>
              </div>
            )}
            {product.length_range && (
              <div>
                <dt className="text-ivory-muted">{dict.product.lengthRange}</dt>
                <dd className="mt-0.5 text-ivory">{product.length_range}</dd>
              </div>
            )}
            {product.pressure && (
              <div>
                <dt className="text-ivory-muted">{dict.product.pressure}</dt>
                <dd className="mt-0.5 text-ivory">{product.pressure}</dd>
              </div>
            )}
            <div>
              <dt className="text-ivory-muted">{dict.product.availability}</dt>
              <dd className="mt-0.5 text-ivory">{product.availability}</dd>
            </div>
          </dl>

          {(product.food_grade || product.medical_grade) && (
            <div className="mt-4 flex gap-2">
              {product.food_grade && (
                <span className="rounded-full bg-accent-soft px-3 py-1 text-xs font-medium text-accent">
                  {dict.product.foodGrade}
                </span>
              )}
              {product.medical_grade && (
                <span className="rounded-full bg-accent-soft px-3 py-1 text-xs font-medium text-accent">
                  {dict.product.medicalGrade}
                </span>
              )}
            </div>
          )}

          <button
            type="button"
            onClick={handleAdd}
            className="focus-ring mt-6 w-full rounded-full bg-accent py-3.5 text-sm font-medium text-ink transition-colors hover:bg-accent-strong sm:w-auto sm:px-8"
          >
            {justAdded || alreadyAdded ? dict.product.addedToRfq : dict.product.addToRfq}
          </button>

          {product.applications.length > 0 && (
            <div className="mt-8">
              <h2 className="text-sm font-medium text-ivory">{dict.product.applications}</h2>
              <div className="mt-2 flex flex-wrap gap-2">
                {product.applications.map((app) => (
                  <span
                    key={app}
                    className="rounded-full border border-border-soft px-3 py-1 text-xs text-ivory-muted"
                  >
                    {app}
                  </span>
                ))}
              </div>
            </div>
          )}

          {product.industries.length > 0 && (
            <div className="mt-4">
              <h2 className="text-sm font-medium text-ivory">{dict.product.industries}</h2>
              <div className="mt-2 flex flex-wrap gap-2">
                {product.industries.map((ind) => (
                  <span
                    key={ind.slug}
                    className="rounded-full border border-border-soft px-3 py-1 text-xs text-ivory-muted"
                  >
                    {lang === "fa" ? ind.name.fa : ind.name.en}
                  </span>
                ))}
              </div>
            </div>
          )}

          <div className="mt-8 flex items-center gap-3 rounded-xl border border-border-soft bg-surface p-4">
            {/* eslint-disable-next-line @next/next/no-img-element */}
            <img
              src={qrCodeUrl(product.slug)}
              alt="QR code"
              width={72}
              height={72}
              className="rounded bg-white p-1"
            />
            <p className="text-xs text-ivory-muted">{dict.product.scanQr}</p>
          </div>
        </div>
      </div>

      {product.specs && (
        <div className="mt-12">
          <h2 className="mb-3 text-lg font-semibold text-ivory">{dict.product.specifications}</h2>
          <SpecTable specs={product.specs} />
        </div>
      )}

      {product.related.length > 0 && (
        <div className="mt-14">
          <h2 className="mb-4 text-lg font-semibold text-ivory">{dict.product.related}</h2>
          <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            {product.related.map((related) => (
              <ProductCard key={related.slug} product={related} />
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
