<?php

namespace App\Exports;

use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReservationExport implements WithMultipleSheets
{
    /**
     * Create a new class instance.
     */
    protected Carbon $startDate;
    protected Carbon $endDate;
    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate->copy()->startOfDay();
        $this->endDate = $endDate->copy()->endOfDay();
    }

    public function sheets(): array
    {
        $reservations = Reservation::whereHas('book')->with(['book', 'book.category', 'book.genres'])
            ->whereBetween('reserved_at', [$this->startDate, $this->endDate])
            ->get();

        $books = $reservations->groupBy('book_id')->map(function ($group) {
            $reservation = $group->first();
            return [
                'book_id' => $reservation->book_id,
                'title' => $reservation->book->title ?? 'Без названия',
                'slug' => $reservation->book->slug ?? '',
                'count' => $group->count(),
            ];
        })->sortByDesc('count')->values();

        $authors = $reservations->groupBy(fn($reservation) => $reservation->book->author_id)->map(function ($group) {
            $author = $group->first()->book->author;

            return [
                'author_id' => $author->id ?? 'Удалено',
                'author_name' => $author->name ?? 'Удалено',
                'count' => $group->count()
            ];
        })->sortByDesc('count')->values();

        $publishers = $reservations->groupBy(fn($reservation) => $reservation->book->publisher_id)->map(function ($group) {
            $publisher = $group->first()->book->publisher;

            return [
                'publisher_id' => $publisher->id ?? 'Удалено',
                'publisher_name' => $publisher->name ?? 'Удалено',
                'count' => $group->count()
            ];
        })->sortByDesc('count')->values();

        $categories = $reservations->groupBy(fn($reservation) => $reservation->book->category_id)->map(function ($group) {
            $category = $group->first()->book->category;

            return [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'count' => $group->count()
            ];
        })->sortByDesc('count')->values();

        return [
            new BooksSheetExport($books, $this->startDate, $this->endDate),
            new AuthorsSheetExport($authors, $this->startDate, $this->endDate),
            new PublishersSheetExport($publishers, $this->startDate, $this->endDate),
            new CategoriesSheetExport($categories, $this->startDate, $this->endDate)
        ];
    }

}
