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
        $data = !empty($args) ? $args[0] : date('Y-m-d');
        $report = new ClicksRegistrationReport($data);
        try {
            $report->printReport();
        } catch (Exception $e) {
            WP_CLI::error($e->getMessage());
        }
    }
}

class ClicksRegistrationReport {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function printReport() {
        global $wpdb;

        $data_inicio = $this->data . ' 00:00:00';
        $data_fim = $this->data . ' 23:59:59';

        $results = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM wp_click_records WHERE date_record BETWEEN %s AND %s", $data_inicio, $data_fim),
            ARRAY_A
        );

        if (empty($results)) {
            throw new Exception("Nenhum registro encontrado para a data $this->data.");
        }

        $total = $wpdb->get_var(
            $wpdb->prepare("SELECT COUNT(*) FROM wp_click_records WHERE date_record BETWEEN %s AND %s", $data_inicio, $data_fim)
        );

        WP_CLI::success("Registros de cliques para o dia $this->data:");
        foreach ($results as $result) {
            WP_CLI::line("Data: " . $result['date_record']);
        }
        WP_CLI::line("Total de cliques: " . $total);

    }
}
