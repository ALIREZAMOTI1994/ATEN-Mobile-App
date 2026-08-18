import { fetchCategories, fetchIndustries, fetchProducts } from "@/lib/api";
import { ProductsContent } from "@/components/ProductsContent";
import type { Metadata } from "next";

export const metadata: Metadata = {
  title: "Products",
};

type SearchParams = {
  category?: string;
  industry?: string;
  q?: string;
  food_grade?: string;
  medical_grade?: string;
  availability?: string;
  page?: string;
};

export default async function ProductsPage({
  searchParams,
}: {
  searchParams: Promise<SearchParams>;
}) {
  const params = await searchParams;

  const filters = {
    category: params.category,
    industry: params.industry,
    q: params.q,
    food_grade: params.food_grade === "1" ? true : undefined,
    medical_grade: params.medical_grade === "1" ? true : undefined,
    availability: params.availability,
    page: params.page ? Number(params.page) : undefined,
    per_page: 24,
  };

  const [productsRes, categoriesRes, industriesRes] = await Promise.all([
    fetchProducts(filters),
    fetchCategories(),
    fetchIndustries(),
  ]);

  return (
    <ProductsContent
      products={productsRes.data}
      meta={productsRes.meta}
      categories={categoriesRes.data}
      industries={industriesRes.data}
      filters={params}
    />
  );
}
