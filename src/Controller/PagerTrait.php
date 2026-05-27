<?php

namespace Drupal\pragmatica\Controller;

trait PagerTrait {
  /**
   * Build pager metadata and visible page window.
   *
   * @param int $total Total number of items.
   * @param int $per_page Items per page.
   * @param int $current_page Current page index (0-based).
   * @param int $max_visible Maximum number of visible page links in the window.
   *
   * @return array
   *   Pager metadata with keys: current, total, per_page, total_pages, pages (array of ['index','label','is_current']).
   */
  private function buildPager(int $total, int $per_page, int $current_page = 0, int $max_visible = 5) : array {
    $total_pages = $per_page > 0 ? (int) ceil($total / $per_page) : 0;

    if ($current_page < 0) {
      $current_page = 0;
    }

    if ($total_pages > 0 && $current_page >= $total_pages) {
      $current_page = $total_pages - 1;
    }

    if ($total_pages <= $max_visible) {
      $start = 0;
      $end = $total_pages - 1;
    }
    else {
      $half = (int) floor($max_visible / 2);
      $start = $current_page - $half;
      if ($start < 0) {
        $start = 0;
      }
      $end = $start + $max_visible - 1;
      if ($end > $total_pages - 1) {
        $end = $total_pages - 1;
        $start = $end - $max_visible + 1;
      }
    }

    $visible_pages = $total_pages > 0 ? range($start, $end) : [];
    $pages = [];
    foreach ($visible_pages as $i) {
      $pages[] = [
        'index' => $i,
        'label' => $i + 1,
        'is_current' => $i === $current_page,
      ];
    }

    return [
      'current' => $current_page,
      'total' => $total,
      'per_page' => $per_page,
      'total_pages' => $total_pages,
      'pages' => $pages,
    ];
  }
}
