<?php

namespace Drupal\drupalorg_projects\Service;

use Drupal\Core\Cache\CacheBackendInterface;
use Opdavies\Drupalorg\Entity\Project;
use Opdavies\Drupalorg\Query\NodeQuery;

class ProjectRetriever {

  /**
   * The MS project cache.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  private $cache;

  /**
   * The project ID.
   *
   * @var int
   */
  private $projectId;

  /**
   * ProjectRetriever constructor.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The MS project cache.
   */
  public function __construct(CacheBackendInterface $cache) {
    $this->cache = $cache;
  }

  /**
   * Set the project ID.
   *
   * @param int $project_id
   *   The project ID.
   *
   * @return \Drupal\drupalorg_projects\Service\ProjectRetriever
   */
  public function setProjectId(int $project_id): ProjectRetriever {
    $this->projectId = $project_id;

    return $this;
  }

  /**
   * Get the project data.
   *
   * @return array
   *   The project data.
   */
  public function getProject(): array {
    if ($cached = $this->cache->get($this->projectId)) {
      $project = $cached->data;
    }
    else {
      $query = (new NodeQuery())->setOptions(['query' => [
        'nid' => $this->projectId,
        'type' => Project::TYPE_MODULE,
      ]]);

      $project = $query->execute()->getContents()->first();

      $this->cache->set($this->projectId, $project, strtotime('tomorrow'));
    }

    return collect([$project])
      ->map(function (\stdClass $item) {
        return new Project($item);
      })
      ->map(function (Project $project) {
        return [
          'title' => $project->getTitle(),
          'downloads' => $project->getDownloads(),
          'stars' => $project->getStars(),
          'url' => $project->get('url'),
        ];
      })
      ->first();
  }

}
