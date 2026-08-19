import { notFound } from "next/navigation";
import { ApiError, fetchProduct } from "@/lib/api";
import { ProductDetailContent } from "@/components/ProductDetailContent";
import type { Metadata } from "next";

export default async function ProductDetailPage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;

  let product;
  try {
    ({ data: product } = await fetchProduct(slug));
  } catch (error) {
    if (error instanceof ApiError && error.status === 404) {
      notFound();
    }
    throw error;
  }

  return <ProductDetailContent product={product} />;
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>;
}): Promise<Metadata> {
  const { slug } = await params;
  try {
    const { data: product } = await fetchProduct(slug);
    return { title: product.name.en, description: product.summary };
  } catch {
    return { title: "Product" };
  }
}
