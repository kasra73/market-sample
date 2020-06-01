export interface PaginatedResult<T> {
    current_page: number;
    data: Array<T>;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_urم: string;
    next_page_url: string|null;
    path: string;
    per_page: number;
    prev_page_url: string|null;
    to: number;
    total: number;
}
