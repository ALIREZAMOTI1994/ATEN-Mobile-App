export type Localized = { en: string; fa: string };

export type Category = {
  slug: string;
  name: Localized;
  blurb: Localized;
  image: string | null;
  product_count?: number;
};

export type Industry = {
  slug: string;
  name: Localized;
};

export type SpecTable = {
  columns: string[];
  rows: (string | number)[][];
};

export type Availability = "In stock" | "Made to order" | "On request";

export type ProductListItem = {
  slug: string;
  sku: string;
  name: Localized;
  category?: { slug: string; name: Localized };
  material: string;
  summary: string;
  thumbnail: string | null;
  size_range: string | null;
  pressure: string | null;
  food_grade: boolean;
  medical_grade: boolean;
  featured: boolean;
  availability: Availability;
};

export type Product = ProductListItem & {
  description: string | null;
  applications: string[];
  industries: Industry[];
  images: string[];
  specs: SpecTable | null;
  length_range: string | null;
  catalog_page: number | null;
  qr_code_url: string;
  related: ProductListItem[];
};

export type Paginated<T> = {
  data: T[];
  meta: {
    current_page: number;
    last_page: number;
    total: number;
    per_page: number;
  };
};

export type RfqItemInput = {
  product_slug?: string;
  product_name?: string;
  quantity: number;
  unit?: string;
  size?: string;
  notes?: string;
};

export type RfqSubmission = {
  type: "single" | "multi" | "bulk" | "project";
  company_name: string;
  contact_name: string;
  email: string;
  phone?: string;
  country?: string;
  message?: string;
  items: RfqItemInput[];
};

export type Rfq = {
  rfq_number: string;
  type: string;
  status: string;
  company_name: string;
  contact_name: string;
  email: string;
  phone: string | null;
  country: string | null;
  message: string | null;
  submitted_at: string | null;
  items?: {
    product_slug: string | null;
    product_name: string;
    sku: string | null;
    quantity: number;
    unit: string;
    size: string | null;
    notes: string | null;
  }[];
};
