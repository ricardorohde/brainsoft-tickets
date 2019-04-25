<?php
include_once __DIR__ . "/../controller/ticket/ticket.ctrl.php";

class TicketChartByRegistryHelper
{
    private static $instance;
    private $ticketController;

    private $labels;
    private $data;
    private $options;
    private $allRegistriesWithTickets;

    private $initialDate;
    private $finalDate;

    public function getLabels()
    {
        return $this->labels;
    }

    public function setLabels($labels)
    {
        $this->labels = $labels;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getInitialDate()
    {
        return $this->initialDate;
    }

    public function setInitialDate($initialDate)
    {
        $this->initialDate = $initialDate;
    }

    public function getFinalDate()
    {
        return $this->finalDate;
    }

    public function setFinalDate($finalDate)
    {
        $this->finalDate = $finalDate;
    }

    function __construct()
    {
        $this->ticketController = TicketController::getInstance();

        @define('OPEN', '[');
        @define('CLOSE', ']');
        @define('DELIMITER', ',');
    }

    public function findAllRegistriesWithTickets()
    {
        $this->allRegistriesWithTickets = $this->ticketController->countByRegistryAndDate($this->initialDate, $this->finalDate);
    }

    public function makeLabelsAndData()
    {
        $count = count($this->allRegistriesWithTickets) - 1;
        $i = 0;
        $data = OPEN;
        $labels = OPEN;

        foreach ($this->allRegistriesWithTickets as $registry) {
            if ($i < $count) {
                $labels = $labels . '"' . $registry['registry'] . '"' . DELIMITER;
                $data = $data . $registry['total'] . DELIMITER;
            } else {
                $labels = $labels . '"' . $registry['registry'] . '"';
                $data = $data . $registry['total'];
            }
            $i++;
        }
        $this->labels = $labels . CLOSE;
        $this->data = $data . CLOSE;
    }

    public function makeBackgroundColors()
    {
        $colors = [
            "rgba(255, 99, 132, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(75, 192, 192, 0.2)",
            "rgba(54, 162, 235, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(201, 203, 207, 0.2)"
        ];

        $newSequenceColors = array();
        $count = round(count($this->allRegistriesWithTickets) / 7) - 1;

        for ($i=0; $i < $count; $i++) {
            for ($j=0; $j < 7; $j++) {
                array_push($newSequenceColors, '"' . $colors[$j] . '"');
            }
        }

        $this->backgroundColors = implode(",", $newSequenceColors);
    }

    public function makeOptions()
    {
        $options = '{
            responsive: true,
            title: {
                display: true,
                position: "top",
                text: "Atendimentos na semana",
                fontSize: 18,
                fontColor: "#111"
            },
            legend: {
                display: true,
                position: "bottom",
                labels: {
                    fontColor: "#333",
                    fontSize: 12
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0
                    }
                }]
            }
        }';

        $this->options = $options;
    }

    public function makeElement()
    {
        $this->findAllRegistriesWithTickets();
        $this->makeLabelsAndData();
        $this->makeBackgroundColors();
        $this->makeOptions();

        $element = '{
            type: "horizontalBar",
            data: {
              labels:' . $this->labels . ',
              datasets: [
                {
                  label: "",
                  data:' . $this->data . ',
                  fill: false,
                  backgroundColor: [' . $this->backgroundColors . '],
                  borderColor: [
                    "rgb(255, 99, 132)",
                    "rgb(255, 159, 64)",
                    "rgb(255, 205, 86)",
                    "rgb(75, 192, 192)",
                    "rgb(54, 162, 235)",
                    "rgb(153, 102, 255)",
                    "rgb(201, 203, 207)"
                  ],
                  borderWidth: 1
                }
              ]
            },
            options: {
                responsive: true,
                legend: {
                    display: false,
                    position: "bottom",
                    labels: {
                        fontColor: "#333",
                        fontSize: 12
                    }
                },
                scales: {
                    xAxes: [{
                        barPercentage: 0.5,
                        barThickness: 6,
                        maxBarThickness: 8,
                        minBarLength: 2,
                        gridLines: {
                            offsetGridLines: true
                        }
                    }]
                }
            }
          }';

        return $element;
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new TicketChartByRegistryHelper();
        }
        return self::$instance;
    }
}
