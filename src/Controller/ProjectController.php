<?php

namespace Drupal\drupalorg_projects\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\drupalorg_projects\Service\ProjectRetriever;
use Drupal\drupalorg_projects\Traits\SplitsStrings;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProjectController extends ControllerBase {

  use SplitsStrings;

  /**
   * The project retriever service.
   *
   * @var \Drupal\drupalorg_projects\Service\ProjectRetriever
   */
  private $projectRetriever;

  /**
   * ProjectController constructor.
   *
   * @param \Drupal\drupalorg_projects\Service\ProjectRetriever $project_retriever
   *   The project retriever service.
   */
  public function __construct(ProjectRetriever $project_retriever) {
    $this->projectRetriever = $project_retriever;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('drupalorg_projects.project_retriever')
    );
  }

  /**
   * Generate a list of projects.
   *
   * @return array
   *   A render array.
   */
  public function index(): array {
    return [
      '#title' => $this->t('Projects'),
      '#theme' => 'item_list',
      '#items' => $this->projectIds(),
      '#empty' => $this->t('There are currently no projects.'),
    ];
  }

  /**
   * Return the project IDs.
   *
   * @return array
   */
  private function projectIds(): array {
    $project_ids = $this->config('drupalorg_projects.settings')->get('project_ids');
    if (is_null($project_ids)) {
      return [];
    }

    return collect($this->splitString($project_ids))
      ->map(function (int $project_id) {
        return $this->projectRetriever
          ->setProjectId($project_id)
          ->getProject();
      })
      ->sortByDesc('downloads')
      ->map(function (array $values) {
        return vsprintf('%s (%s): %s downloads, %s stars', [
          $values['title'],
          $values['url'],
          number_format($values['downloads']),
          number_format($values['stars']),
        ]);
      })
      ->values()
      ->toArray();
  }
}
