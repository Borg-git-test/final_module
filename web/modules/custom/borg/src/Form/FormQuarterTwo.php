<?php

namespace Drupal\borg\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Form.
 */
class FormQuarterTwo extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'quarterTwo';
  }

  /**
   * The associative array with quarters.
   *
   * @var string[]
   */
  protected $quarters = [
    'q1' => [
      'jan',
      'feb',
      'mar',
    ],
    'q2' => [
      'apr',
      'may',
      'jun',
    ],
    'q3' => [
      'jul',
      'aug',
      'sep',
    ],
    'q4' => [
      'oct',
      'nov',
      'dec',
    ],
  ];

  /**
   * The associative array where key the table id and value the number of rows.
   *
   * [1 => 1];
   *
   * @var int[]
   */
  protected $tableRows;

  /**
   * The start id value for rows and tables.
   *
   * Do not use 0 or a negative value.
   *
   * @var int
   */
  protected $startKey = 1;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    // If $rowId = NULL set start value.
    $this->tableRows = is_null($this->tableRows) ? [$this->startKey => $this->startKey] : $this->tableRows;

    // This ajax button submit addTable function and update form.
    $form['add_table'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Table'),
      '#submit' => ['::addTable'],
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'wrapper' => 'quarter',
      ],
    ];

    // This ajax submit button start validate and update form.
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'wrapper' => 'quarter',
      ],
    ];

    // The container with tables.
    $form['tables'] = [
      '#attributes' => ['id' => 'tables'],
      '#type' => 'container',
      '#tree' => TRUE,
    ];

    // Add id attribute to form for ajax wrapper.
    $form['#attributes'] = [
      'id' => 'quarter',
    ];

    // Add css library.
    $form['#attached']['library'][] = 'borg/style';

    // Build tables.
    foreach ($this->tableRows as $tableId => $rows) {
      $this->buildTable($form, $tableId);
    }

    return $form;
  }

  /**
   * Rebuild form function from ajax.
   */
  public function ajaxCallback($pleaseWork) {
    return $pleaseWork;
  }

  /**
   * Add row to table where id is button name and rebuild form.
   */
  public function addRows(array &$form, FormStateInterface $form_state) {
    $id = $form_state->getTriggeringElement()['#name'];
    ++$this->tableRows[$id];
    $form_state->setRebuild();
  }

  /**
   * Add table with one row and rebuild form.
   */
  public function addTable(array &$form, FormStateInterface $form_state) {
    $this->tableRows[] = $this->startKey;
    $form_state->setRebuild();
  }

  /**
   * Build table with multiple rows.
   */
  public function buildTable(array &$form, int $tableId): array {

    // Header for table.
    $header = [
      'year' => t('Year'),
      'jan' => t('Jan'),
      'feb' => t('Feb'),
      'mar' => t('Mar'),
      'q1' => t('Q1'),
      'apr' => t('Apr'),
      'may' => t('May'),
      'jun' => t('Jun'),
      'q2' => t('Q2'),
      'jul' => t('Jul'),
      'aug' => t('Aug'),
      'sep' => t('Sep'),
      'q3' => t('Q3'),
      'oct' => t('Oct'),
      'nov' => t('Nov'),
      'dec' => t('Dec'),
      'q4' => t('Q4'),
      'ytd' => t('YTD'),
    ];

    // Container for table and 'add row' button.
    $form['tables'][$tableId] = [
      'add_row' => [
        '#type' => 'submit',
        '#name' => $tableId,
        '#value' => $this->t('Add Year'),
        '#submit' => ['::addRows'],
        '#ajax' => [
          'callback' => '::ajaxCallback',
          'wrapper' => 'quarter',
        ],
      ],
      'table' => [
        '#type' => 'table',
        '#header' => $header,
        '#attributes' => ['id' => $tableId],
      ],
    ];

    // Build multiple rows with decrement for add previous years in top.
    for ($i = $this->tableRows[$tableId]; $i >= $this->startKey; $i--) {
      $year = date('Y') - $i + $this->startKey;
      $form['tables'][$tableId]['table'][$i] = $this->buildRow($year);
    }

    return $form;
  }

  /**
   * Build row.
   */
  public function buildRow($year): array {

    $row['year'] = [
      '#markup' => $year,
      '#prefix' => '<div class="year">',
      '#suffix' => '</div>',
    ];

    foreach ($this->quarters as $quarter => $months) {
      foreach ($months as $month) {
        $row[$month] = [
          '#type' => 'number',
          '#step' => 0.0001,
          '#attributes' => [
            'style' => [
              'width: 100%;',
              'outline: none;',
            ],
          ],
        ];
      }
      $row[$quarter] = [
        '#markup' => '0',
        '#prefix' => '<div class="quarter">',
        '#suffix' => '</div>',
      ];
    }
    $row['ytd'] = [
      '#markup' => '0',
      '#prefix' => '<div class="result">',
      '#suffix' => '</div>',
    ];

    return $row;
  }

  /**
   * Validation function for tables.
   */
  public function validateTable($form_state): bool {

    // Record all values from tables to massive.
    $values = $form_state->getValues()['tables'];
    $oneRowTable = [];

    foreach ($values as $key => $table) {
      $mergeRows[$key] = [];

      // Record all rows in one numeric array.
      foreach (array_reverse($table['table']) as $row) {
        $mergeRows[$key] = array_merge($mergeRows[$key], array_values(array_reverse($row)));
      }

      // Record all filled field in one array.
      $filledRows[$key] = array_filter($mergeRows[$key], function ($value) {
        return $value !== "";
      });

      // If field is empty return false.
      if (count($filledRows[$key]) === 0) {
        return FALSE;
      }

      // If line break return false.
      elseif ((array_key_last($filledRows[$key]) - array_key_first($filledRows[$key])) != count($filledRows[$key]) - 1) {
        return FALSE;
      }

      // Record tables with one row.
      elseif (count($table['table']) === 1) {
        $oneRowTable[$key] = $table['table'][$this->startKey];
      }
    }

    // If all tables has one row.
    if (count($oneRowTable) === count($values)) {

      // Multiple tables with one row must be filled for the same period.
      foreach ($oneRowTable as $key => $table) {

        // Record all filled field in one array.
        $filledOneRow[$key] = array_filter(array_values($table), function ($row) {
          return $row !== "";
        });

        // Multiple tables must be filled for the same period.
        if ((array_key_last($filledOneRow[$this->startKey]) !== array_key_last($filledOneRow[$key])) || (array_key_first($filledOneRow[$this->startKey]) !== array_key_first($filledOneRow[$key]))) {
          return FALSE;
        }
      }
    }

    return TRUE;
  }

  /**
   * Calculation function for tables.
   */
  public function calculateTable(array &$form, FormStateInterface $form_state): array {

    // Record all values from tables to massive.
    $values = $form_state->getValues()['tables'];

    foreach ($values as $key => $table) {
      foreach ($table['table'] as $row => $value) {
        $summaryYear = 0;
        foreach ($this->quarters as $quarter => $months) {
          $summaryMonths = 0;
          foreach ($months as $month) {
            // Calculate summary months.
            $summaryMonths += (float) $value[$month];
          }
          // Calculate quarter by the formula ((М1+М2+М3)+1)/3.
          $summaryQuart = ($summaryMonths + 1) / 3;
          // Record quarter result in appropriate cell with rounding.
          $form['tables'][$key]['table'][$row][$quarter]['#markup'] = round($summaryQuart, 2);
          // Calculate summary year.
          $summaryYear += $summaryQuart;
        }
        // Calculate year by the formula ((К1+К2+К3+К4)+1)/4.
        $summaryYear = $summaryYear == 0 ? 0 : ($summaryYear + 1) / 4;
        // Record year result in appropriate cell with rounding.
        $form['tables'][$key]['table'][$row]['ytd']['#markup'] = round($summaryYear, 2);
      }

    }
    return $form;
  }

  /**
   * Submit function for all form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state): array {

    // Call the validation function.
    $valid = $this->validateTable($form_state);

    // If form valid.
    if ($valid) {
      // Valid message.
      $this->messenger()->addStatus("Valid");
      // Calculate and display result.
      $this->calculateTable($form, $form_state);
    }
    // If form invalid.
    else {
      // Invalid message.
      $this->messenger()->addError("Invalid");
    }

    return $form;
  }

}
