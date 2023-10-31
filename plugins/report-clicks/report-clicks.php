<?php
/*
Plugin Name: Report Clicks
Description: Report clicks saves in the database.
Author: Lyssa
Version: 1.0
*/

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('report-clicks', 'ClicksReportCommand');
}

class ClicksReportCommand {
    public function __invoke($args) {
        $date = !empty($args) ? $args[0] : date('Y-m-d');
        $report = new ClicksRegistrationReport($date);
        try {
            $report->printReport();
        } catch (Exception $e) {
            WP_CLI::error($e->getMessage());
        }
    }
}

class ClicksRegistrationReport {
    private $date;

    public function __construct($date) {
        $this->date = $date;
    }

    public function printReport() {
        global $wpdb;

        $initial_date = $this->date . ' 00:00:00';
        $end_date = $this->date . ' 23:59:59';

        $results = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM wp_click_records WHERE date_record BETWEEN %s AND %s", $initial_date, $end_date),
            ARRAY_A
        );

        if (empty($results)) {
            throw new Exception("Nenhum registro encontrado para a date $this->date.");
        }

        $total = $wpdb->get_var(
            $wpdb->prepare("SELECT COUNT(*) FROM wp_click_records WHERE date_record BETWEEN %s AND %s", $initial_date, $end_date)
        );

        WP_CLI::success("Registros de cliques para o dia $this->date:");
        foreach ($results as $result) {
            WP_CLI::line("Data: " . $result['date_record']);
        }
        WP_CLI::line("Total de cliques: " . $total);

    }
}
