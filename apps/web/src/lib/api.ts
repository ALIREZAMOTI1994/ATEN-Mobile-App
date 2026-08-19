import type {
  Category,
  Industry,
  Paginated,
  Product,
  ProductListItem,
  Rfq,
  RfqSubmission,
} from "./types";

const PUBLIC_API_BASE = process.env.NEXT_PUBLIC_API_URL ?? "http://localhost:8000";

// Server-side rendering runs inside the Docker network, where the API is
// reachable at an internal service hostname rather than the browser-facing
// URL. INTERNAL_API_URL lets docker-compose point SSR fetches there while
// the browser keeps using NEXT_PUBLIC_API_URL.
const API_BASE =
  typeof window === "undefined" ? (process.env.INTERNAL_API_URL ?? PUBLIC_API_BASE) : PUBLIC_API_BASE;

export class ApiError extends Error {
  status: number;
  errors?: Record<string, string[]>;

  constructor(message: string, status: number, errors?: Record<string, string[]>) {
    super(message);
    this.status = status;
    this.errors = errors;
  }
}

async function request<T>(path: string, init?: RequestInit): Promise<T> {
  const res = await fetch(`${API_BASE}/api/v1${path}`, {
    ...init,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      ...init?.headers,
    },
  });

  if (!res.ok) {
    const body = await res.json().catch(() => null);
    throw new ApiError(body?.message ?? res.statusText, res.status, body?.errors);
  }

  return res.json() as Promise<T>;
}

export function fetchCategories(): Promise<{ data: Category[] }> {
  return request("/categories", { next: { revalidate: 3600 } } as RequestInit);
}

export type ProductFilters = {
  category?: string;
  industry?: string;
  q?: string;
  food_grade?: boolean;
  medical_grade?: boolean;
  featured?: boolean;
  availability?: string;
  page?: number;
  per_page?: number;
};

export function fetchProducts(filters: ProductFilters = {}): Promise<Paginated<ProductListItem>> {
  const params = new URLSearchParams();
  Object.entries(filters).forEach(([key, value]) => {
    if (value === undefined || value === null || value === "") return;
    params.set(key, typeof value === "boolean" ? (value ? "1" : "0") : String(value));
  });
  const qs = params.toString();
  return request(`/products${qs ? `?${qs}` : ""}`, {
    next: { revalidate: 60 },
  } as RequestInit);
}

export function fetchProduct(slug: string): Promise<{ data: Product }> {
  return request(`/products/${slug}`, { next: { revalidate: 60 } } as RequestInit);
}

export function fetchIndustries(): Promise<{ data: Industry[] }> {
  return request("/industries", { next: { revalidate: 3600 } } as RequestInit);
}

export function submitRfq(payload: RfqSubmission): Promise<{ data: Rfq }> {
  return request("/rfqs", {
    method: "POST",
    body: JSON.stringify(payload),
    cache: "no-store",
  });
}

export function trackRfq(rfqNumber: string, email: string): Promise<{ data: Rfq }> {
  return request(`/rfqs/${encodeURIComponent(rfqNumber)}?email=${encodeURIComponent(email)}`, {
    cache: "no-store",
  });
}

export type ContactSubmission = {
  name: string;
  company_name?: string;
  email: string;
  phone?: string;
  message: string;
};

export function submitContact(payload: ContactSubmission): Promise<{ message: string }> {
  return request("/contact", {
    method: "POST",
    body: JSON.stringify(payload),
    cache: "no-store",
  });
}

// These build URLs that end up in the browser's DOM (img/src attributes),
// so they always use the public-facing API URL, never the internal one.
export function qrCodeUrl(slug: string): string {
  return `${PUBLIC_API_BASE}/api/v1/products/${slug}/qrcode`;
}

export function productImageUrl(path: string | null): string {
  if (!path) return "/placeholder-product.png";
  return `${PUBLIC_API_BASE}/storage/${path}`;
}
