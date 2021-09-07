<?php

namespace Drupal\borg\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Form.
 */
class FormQuarter extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'quarter';
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
        'callback' => '::pleaseWork',
        'wrapper' => 'quarter',
      ],
    ];

    // This ajax submit button start validate and update form.
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::pleaseWork',
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

    // Build tables.
    foreach ($this->tableRows as $tableId => $rows) {
      $this->buildTable($form, $tableId);
    }

    return $form;
  }

  /**
   * Rebuild form function for ajax.
   */
  function pleaseWork($pleaseWork) {
    return $pleaseWork;
  }

  /**
   * Add row to table with id is name button.
   */
  function addRows(array &$form, FormStateInterface $form_state) {
    $id = $form_state->getTriggeringElement()['#name'];
    ++$this->tableRows[$id];
    $form_state->setRebuild();
  }

  /**
   * Add table with one row.
   */
  function addTable(array &$form, FormStateInterface $form_state) {
    $this->tableRows[] = $this->startKey;
    $form_state->setRebuild();
  }

//  public function buildTables(array &$form) {
//    foreach ($this->tableRows as $tableId => $rows) {
//      $this->buildTable($form, $tableId);
//    }
//    return $form;
//  }

  public function buildTable(array &$form, int $tableId) {

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

    $form['tables'][$tableId] = [
      'add_row' => [
        '#type' => 'submit',
        '#name' => $tableId,
        '#value' => $this->t('Add Year'),
        '#submit' => ['::addRows'],
        '#ajax' => [
          'callback' => '::pleaseWork',
          'wrapper' => 'quarter',
        ],
      ],

      'table' => [
        '#type' => 'table',
        '#header' => $header,
        '#attributes' => ['id' => $tableId],
      ],
    ];

    for ($i = $this->tableRows[$tableId]; $i >= $this->startKey; $i--) {
      $year = date('Y') - $i + $this->startKey;
      $form['tables'][$tableId]['table'][$i] = $this->buildRow($year);
    }

    return $form;
  }

  public function buildRow($year) {

    $row['year'] = ['#markup' => $year];

    foreach ($this->quarters as $quarter => $months) {
      foreach ($months as $month) {
        $row[$month] = [
          '#type' => 'number',
          '#attributes' => [
            'style' => 'width: 7em;',
          ],
        ];
      }
      $row[$quarter] = ['#markup' => '0'];
    }
    $row['ytd'] = ['#markup' => '0'];

    return $row;
  }

  public function validateTable(array &$form, FormStateInterface $form_state) {

    $values = $form_state->getValues()['tables'];
//    $oneRowId = [];
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
      if (count($filledRows[$key]) == 0) {
        return FALSE;
      }

      // If line break return false.
      elseif ((array_key_last($filledRows[$key]) - array_key_first($filledRows[$key])) != count($filledRows[$key]) - 1) {
        return FALSE;
      }

      // Multiple tables must be filled for the same period or return false.
//      elseif ((array_key_last($filledRows[$this->startKey]) !== array_key_last($filledRows[$key])) || (array_key_first($filledRows[$this->startKey]) !== array_key_first($filledRows[$key]))) {
//        return FALSE;
//      }


      // Record tables with one row.
      if (count($table['table']) === 1) {
        $oneRowTable[$key] = $table['table'][$this->startKey];
      }
    }

    // Multiple tables with one row must be filled for the same period.
    if (count($oneRowTable) === count($values)) {
      foreach ($oneRowTable as $key => $table) {
        $filledOneRow[$key] = array_filter(array_values($table), function ($row) {
          return $row !== "";
        });
        if ((array_key_last($filledOneRow[$this->startKey]) !== array_key_last($filledOneRow[$key])) || (array_key_first($filledOneRow[$this->startKey]) !== array_key_first($filledOneRow[$key]))) {
          return FALSE;
        }
      }
    }

    return TRUE;
  }


  public function CalculateTable(array &$form, FormStateInterface $form_state) {

    $values = $form_state->getValues()['tables'];

    foreach ($values as $key => $table) {
      foreach ($table['table'] as $row => $value) {
        $summaryYear = 0;
        foreach ($this->quarters as $quarter => $months) {
          $summaryMonths = 0;
          foreach ($months as $month) {
            $summaryMonths += (float) $value[$month];
          }
          $summaryQuart = $summaryMonths == 0 ? 0 : ($summaryMonths + 1) / 3;
          $form['tables'][$key]['table'][$row][$quarter]['#markup'] = round($summaryQuart, 2);
          $summaryYear += $summaryQuart;
        }
        $summaryYear = $summaryYear == 0 ? 0 : ($summaryYear + 1) / 4;
        $form['tables'][$key]['table'][$row]['ytd']['#markup'] = round($summaryYear, 2);
      }

    }
    return $form;
  }


    public function submitForm(array &$form, FormStateInterface $form_state) {

    $valid = $this->validateTable($form, $form_state);
//    $values = $form_state->getValues()['tables'];
//    $this->messenger()->addMessage(print_r($values, TRUE));

    if ($valid) {
      $this->messenger()->addStatus("Valid");
      $this->CalculateTable($form, $form_state);
    }
    else {
      $this->messenger()->addError("Invalid");
    }

    return $form;
  }

}
