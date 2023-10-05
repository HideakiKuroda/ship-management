<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Exception;

class ScheduleController extends Controller
{
    public function getInspectionDates(Request $request, $shipType, $baseDate)
    {
        try {
            $inspectionDates = $this->calculateInspectionDates($shipType, $baseDate);
            return response()->json($inspectionDates);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function calculateInspectionDates($shipType, $baseDate)
    {
        $dates = new DateTime($baseDate);
        $inspectionPeriods = [];

        if ($shipType == 1) {
            $inspectionPeriods['midterm1'] = [
                'start' => $dates->modify('-39 months')->format('Y-m-d'),
                'end' => $dates->modify('+18 months')->format('Y-m-d'),
            ];

            $inspectionPeriods['regular1'] = [
                'start' => $dates->modify('-18 months +3 months')->format('Y-m-d'),
                'end' => $dates->format('Y-m-d'),
            ];

            $dates->modify('+5 years');
            $inspectionPeriods['baseDate2'] = $dates->format('Y-m-d');

            $inspectionPeriods['midterm2'] = [
                'start' => $dates->modify('-39 months')->format('Y-m-d'),
                'end' => $dates->modify('+18 months')->format('Y-m-d'),
            ];

            $inspectionPeriods['regular2'] = [
                'start' => $dates->modify('-18 months +3 months')->format('Y-m-d'),
                'end' => $dates->format('Y-m-d'),
            ];

        } elseif ($shipType == 2) {
            $inspectionPeriods['midterm1'] = [
                'start' => $dates->modify('-39 months')->format('Y-m-d'),
                'end' => $dates->modify('+6 months')->format('Y-m-d'),
            ];

            $inspectionPeriods['regular1'] = [
                'start' => $dates->modify('-6 months +3 months')->format('Y-m-d'),
                'end' => $dates->format('Y-m-d'),
            ];

            $dates->modify('+6 years');
            $inspectionPeriods['baseDate2'] = $dates->format('Y-m-d');

            $inspectionPeriods['midterm2'] = [
                'start' => $dates->modify('-39 months')->format('Y-m-d'),
                'end' => $dates->modify('+6 months')->format('Y-m-d'),
            ];

            $inspectionPeriods['regular2'] = [
                'start' => $dates->modify('-6 months +3 months')->format('Y-m-d'),
                'end' => $dates->format('Y-m-d'),
            ];
        } else {
            throw new Exception('Invalid ship type provided.');
        }

        return $inspectionPeriods;
    }
}
