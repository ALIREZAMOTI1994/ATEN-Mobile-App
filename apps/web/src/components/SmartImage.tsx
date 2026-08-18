"use client";

import Image, { type ImageProps } from "next/image";
import { useState } from "react";

const FALLBACK = "/placeholder-product.png";

// unoptimized: product photos are served by the API container, which the
// Next.js image optimizer (running server-side) may not be able to reach
// under the same URL a browser uses (e.g. behind Docker networking). The
// browser fetches these directly instead of proxying through /_next/image.
export function SmartImage({ src, alt, ...props }: ImageProps) {
  const [erroredSrc, setErroredSrc] = useState<ImageProps["src"] | null>(null);
  const current = erroredSrc === src ? FALLBACK : src;

  return (
    <Image
      {...props}
      alt={alt}
      src={current}
      unoptimized
      onError={() => setErroredSrc(src)}
    />
  );
}
