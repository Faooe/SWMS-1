<?php

namespace Tests\Unit;

use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class SharedPaginationTest extends TestCase
{
    public function test_shared_links_preserve_filters_and_named_pages(): void
    {
        $paginator = new LengthAwarePaginator(range(1, 10), 25, 10, 2, [
            'path' => '/payments', 'pageName' => 'payment_page',
        ]);
        $html = (string) $paginator->appends(['status' => 'Pending'])->links('pagination.shared');
        $this->assertStringContainsString('Sebelumnya', $html);
        $this->assertStringContainsString('Berikutnya', $html);
        $this->assertStringContainsString('status=Pending', $html);
        $this->assertStringContainsString('payment_page=3', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    public function test_livewire_controls_use_named_page_actions_and_disable_boundaries(): void
    {
        $paginator = new LengthAwarePaginator(range(1, 10), 20, 10, 1, ['pageName' => 'team_page']);
        $html = (string) $paginator->links('pagination.shared', ['livewire' => true]);
        $this->assertStringContainsString('nextPage(\'team_page\')', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringNotContainsString('previousPage(', $html);
    }
}
