import { fetchCategories, fetchProducts } from "@/lib/api";
import { HomeContent } from "@/components/HomeContent";

export default async function HomePage() {
  const [categoriesRes, featuredRes] = await Promise.all([
    fetchCategories(),
    fetchProducts({ featured: true, per_page: 8 }),
  ]);

  return (
    <HomeContent categories={categoriesRes.data} featured={featuredRes.data} />
  );
}

// Rendered per-request rather than at build time: the API (and catalog data)
// isn't guaranteed to be reachable while the Docker image is being built.
export const dynamic = "force-dynamic";
