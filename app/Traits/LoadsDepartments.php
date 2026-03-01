<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

trait LoadsDepartments
{
    /**
     * Returns departments grouped by state for use in grouped <select> dropdowns.
     *
     * Structure:
     *   [
     *     [ 'id' => 9,  'title' => 'Lagos State',   'centers' => [ {id, title}, ... ] ],
     *     [ 'id' => 34, 'title' => 'Anambra State',  'centers' => [ ... ] ],
     *     ...
     *   ]
     *
     * Cached for 6 hours — departments almost never change.
     */
    protected function getGroupedDepartments(): array
    {
        return Cache::remember('sys_dept_grouped', 21600, function () {
            // States: children of id=1 ("All States") that are themselves parents
            $states = DB::table('sys_dept as s')
                ->select('s.id', 's.title', 's.sort')
                ->where('s.pid', 1)          // direct children of root
                ->where('s.status', 1)
                ->orderBy('s.sort')
                ->orderBy('s.title')
                ->get();

            $grouped = [];
            foreach ($states as $state) {
                $centers = DB::table('sys_dept')
                    ->select('id', 'title', 'sort')
                    ->where('pid', $state->id)
                    ->where('status', 1)
                    ->orderBy('sort')
                    ->orderBy('title')
                    ->get();

                // Only include states that have at least one active center
                if ($centers->isEmpty()) {
                    continue;
                }

                $grouped[] = [
                    'id'      => $state->id,
                    'title'   => $state->title,
                    'centers' => $centers,
                ];
            }

            return $grouped;
        });
    }

    /**
     * Flat list of all active center departments (id + title), cached 6 hours.
     * Used for validation, search, and places that don't need grouping.
     */
    protected function getFlatDepartments()
    {
        return Cache::remember('sys_dept_active', 21600, fn() =>
            DB::table('sys_dept')
                ->select('id', 'title')
                ->where('status', 1)
                ->where('pid', '!=', 0)     // exclude root "All States"
                ->orderBy('title')
                ->get()
        );
    }

    /**
     * Flat id→title lookup map (includes both states and centers), cached 6 hours.
     * Use this to resolve a selected department/state ID to its display name.
     *
     * @return array<int, string>   e.g. [9 => 'Lagos State', 11 => 'Ojodu Center', ...]
     */
    protected function getDeptLookup(): array
    {
        return Cache::remember('sys_dept_lookup', 21600, fn() =>
            DB::table('sys_dept')
                ->select('id', 'title')
                ->where('status', 1)
                ->get()
                ->pluck('title', 'id')
                ->toArray()
        );
    }

    /**
     * Given a dept_id value that may be either a STATE id or a CENTER id,
     * return an array of center IDs to filter on.
     *
     * - If $id is a state (pid=1), return all its child center IDs.
     * - If $id is a center (pid=state), return [$id].
     * - If $id is empty/null, return [].
     *
     * @param  int|string|null  $id
     * @return array<int>
     */
    protected function resolveDeptFilter($id): array
    {
        if (empty($id)) {
            return [];
        }

        $id = (int) $id;

        $row = DB::table('sys_dept')->select('id', 'pid')->where('id', $id)->first();
        if (!$row) {
            return [$id];
        }

        // It's a state node (pid === 1 means it's a direct child of the root)
        if ((int) $row->pid === 1) {
            return DB::table('sys_dept')
                ->where('pid', $id)
                ->where('status', 1)
                ->pluck('id')
                ->toArray();
        }

        // It's a center (or anything else) — filter by it directly
        return [$id];
    }
}
