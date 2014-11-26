<?php

namespace Backend\Modules\Jobs\Engine;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Exception;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language as BL;
use Backend\Modules\Tags\Engine\Model as BackendTagsModel;

/**
 * In this file we store all generic functions that we will be using in the jobs module
 *
 * @author Bart De Clercq <info@lexxweb.be>
 * @author Tim van Wolfswinkel <tim@reclame-mediabureau.nl>
 */
class Model
{
    const QRY_DATAGRID_BROWSE =
        'SELECT i.id, i.category_id, i.title, p.title AS client, i.client_id, i.hidden, i.sequence
         FROM jobs AS i
         LEFT OUTER JOIN jobs_clients AS p ON p.id = i.client_id
         WHERE i.language = ? AND i.category_id = ?
         ORDER BY i.sequence ASC';

    const QRY_DATAGRID_BROWSE_CATEGORIES =
        'SELECT i.id, i.title, COUNT(p.id) AS num_items, i.sequence
         FROM jobs_categories AS i
         LEFT OUTER JOIN jobs AS p ON i.id = p.category_id AND p.language = i.language
         WHERE i.language = ?
         GROUP BY i.id
         ORDER BY i.sequence ASC';

    const QRY_DATAGRID_BROWSE_CLIENTS =
        'SELECT i.id, i.title, i.sequence
         FROM jobs_clients AS i
         WHERE i.language = ?
         GROUP BY i.id
         ORDER BY i.sequence ASC';

    const QRY_DATAGRID_BROWSE_IMAGES =
        'SELECT i.id, i.job_id, i.filename, i.title, i.sequence
         FROM jobs_images AS i
         WHERE i.job_id = ?
         GROUP BY i.id';

    const QRY_DATAGRID_BROWSE_FILES =
        'SELECT i.id, i.job_id, i.filename, i.title, i.sequence
         FROM jobs_files AS i
         WHERE i.job_id = ?
         GROUP BY i.id';

    const QRY_DATAGRID_BROWSE_VIDEOS =
        'SELECT i.id, i.job_id, i.embedded_url, i.title, i.sequence
         FROM jobs_videos AS i
         WHERE i.job_id = ?
         GROUP BY i.id';

    /**
     * Delete a question
     *
     * @param int $id
     */
    public static function delete($id)
    {
        $id = (int)$id;
        $jobFilesPath = FRONTEND_FILES_PATH . '/jobs/' . $id;
        \SpoonDirectory::delete($jobFilesPath);
        $job = self::get($id);
        if (!empty($job)) {
            $database = BackendModel::getContainer()->get('database');
            $database->delete('meta', 'id = ?', array((int)$job['meta_id']));
            $database->delete('jobs_related', 'job_id = ? OR related_job_id = ?', array($id, $id));
            $database->delete('jobs_images', 'job_id = ?', array($id));
            $database->delete('jobs_videos', 'job_id = ?', array($id));
            $database->delete('jobs_files', 'job_id = ?', array($id));
            $database->delete('jobs', 'id = ?', array($id));
            BackendTagsModel::saveTags($id, '', 'jobs');
        }
    }

    /**
     * Delete a specific category
     *
     * @param int $id
     */
    public static function deleteCategory($id)
    {
        $id = (int)$id;

        $db = BackendModel::getContainer()->get('database');
        $item = self::getCategory($id);

        // build extra
        $extra = array('id' => $item['extra_id'],
            'module' => 'jobs',
            'type' => 'widget',
            'action' => 'category');

        // delete extra
        $db->delete('modules_extras', 'id = ? AND module = ? AND type = ? AND action = ?', array($extra['id'], $extra['module'], $extra['type'], $extra['action']));

        // delete blocks with this item linked
        $db->delete('pages_blocks', 'extra_id = ?', array($item['extra_id']));

        if (!empty($item)) {
            $db->delete('meta', 'id = ?', array($item['meta_id']));
            $db->delete('jobs_categories', 'id = ?', array((int)$id));
            $db->update('jobs', array('category_id' => null), 'category_id = ?', array($id));

            // invalidate the cache for the jobs
            BackendModel::invalidateFrontendCache('jobs', BL::getWorkingLanguage());
        }
    }

    /**
     * Is the deletion of a category allowed?
     *
     * @param int $id
     * @return bool
     */
    public static function deleteCategoryAllowed($id)
    {
        // get result
        $result = (BackendModel::getContainer()->get('database')->getVar(
                'SELECT 1
                 FROM jobs AS i
                 WHERE i.category_id = ? AND i.language = ?
                 LIMIT 1',
                array((int)$id, BL::getWorkingLanguage())) == 0);

        // exception
        if (!BackendModel::getModuleSetting('jobs', 'allow_multiple_categories', true) && self::getCategoryCount() == 1) {
            return false;
        } else return $result;
    }

    /**
     * Delete a specific client
     *
     * @param int $id
     */
    public static function deleteClient($id)
    {
        $db = BackendModel::getContainer()->get('database');
        $item = self::getClient($id);

        if (!empty($item)) {
            $db->delete('meta', 'id = ?', array($item['meta_id']));
            $db->delete('jobs_clients', 'id = ?', array((int)$id));
            $db->update('jobs', array('client_id' => null), 'client_id = ?', array((int)$id));

            // invalidate the cache for the jobs
            BackendModel::invalidateFrontendCache('Jobs', BL::getWorkingLanguage());
        }
    }

    /**
     * Is the deletion of a client allowed?
     *
     * @param int $id
     * @return bool
     */
    public static function deleteClientAllowed($id)
    {
        // get result
        $result = (BackendModel::getContainer()->get('database')->getVar(
                'SELECT 1
                 FROM jobs AS i
                 WHERE i.client_id = ? AND i.language = ?
                 LIMIT 1',
                array((int)$id, BL::getWorkingLanguage())) == 0);

        // exception
        if (!BackendModel::getModuleSetting('jobs', 'allow_multiple_clients', true) && self::getClientCount() == 1) {
            return false;
        } else return $result;
    }

    /**
     * @param array $ids
     */
    public static function deleteImage(array $ids)
    {
        if (empty($ids)) return;

        foreach ($ids as $id) {
            $item = self::getImage($id);
            $job = self::get($item['job_id']);

            // delete image reference from db
            BackendModel::getContainer()->get('database')->delete('jobs_images', 'id = ?', array($id));

            // delete image from disk
            $basePath = FRONTEND_FILES_PATH . '/jobs/' . $item['job_id'];
            \SpoonFile::delete($basePath . '/source/' . $item['filename']);
            \SpoonFile::delete($basePath . '/64x64/' . $item['filename']);
            \SpoonFile::delete($basePath . '/128x128/' . $item['filename']);
            \SpoonFile::delete($basePath . '/' . BackendModel::getModuleSetting('jobs', 'width1') . 'x' . BackendModel::getModuleSetting('jobs', 'height1') . '/' . $item['filename']);
            \SpoonFile::delete($basePath . '/' . BackendModel::getModuleSetting('jobs', 'width2') . 'x' . BackendModel::getModuleSetting('jobs', 'height2') . '/' . $item['filename']);
            \SpoonFile::delete($basePath . '/' . BackendModel::getModuleSetting('jobs', 'width3') . 'x' . BackendModel::getModuleSetting('jobs', 'height3') . '/' . $item['filename']);
        }

        BackendModel::invalidateFrontendCache('slideshowCache');
    }

    /**
     * @param array $ids
     */
    public static function deleteFile(array $ids)
    {
        if (empty($ids)) return;

        foreach ($ids as $id) {
            $item = self::getFile($id);
            $job = self::get($item['job_id']);

            // delete file reference from db
            BackendModel::getContainer()->get('database')->delete('jobs_files', 'id = ?', array($id));

            // delete file from disk
            $basePath = FRONTEND_FILES_PATH . '/jobs/' . $item['job_id'];
            \SpoonFile::delete($basePath . '/source/' . $item['filename']);
        }
    }

    /**
     * @param array $ids
     */
    public static function deleteVideo(array $ids)
    {
        if (empty($ids)) return;

        foreach ($ids as $id) {
            $item = self::getVideo($id);
            $job = self::get($item['job_id']);

            // delete video reference from db
            BackendModel::getContainer()->get('database')->delete('jobs_videos', 'id = ?', array($id));
        }
    }

    /**
     * Delete related job
     *
     * @param int The job id
     * @param int The related job id
     */
    public static function deleteRelatedJobs($jobId, $relatedJobsId = null)
    {
        if (isset($relatedJobsId)) {
            BackendModel::getContainer()->get('database')->delete('jobs_related', 'job_id = ? AND related_job_id = ?', array((int)$jobId, (int)$relatedJobsId));
        } else {
            BackendModel::getContainer()->get('database')->delete('jobs_related', 'job_id = ?', array((int)$jobId));
        }
    }

    /**
     * Does the question exist?
     *
     * @param int $id
     * @return bool
     */
    public static function exists($id)
    {
        return (bool)BackendModel::getContainer()->get('database')->getVar(
            'SELECT 1
              FROM jobs AS i
             WHERE i.id = ? AND i.language = ?
             LIMIT 1',
            array((int)$id, BL::getWorkingLanguage()));
    }

    /**
     * Does the category exist?
     *
     * @param int $id
     * @return bool
     */
    public static function existsCategory($id)
    {
        return (bool)BackendModel::getContainer()->get('database')->getVar(
            'SELECT 1
             FROM jobs_categories AS i
             WHERE i.id = ? AND i.language = ?
             LIMIT 1',
            array((int)$id, BL::getWorkingLanguage()));
    }

    /**
     * Does the client exist?
     *
     * @param int $id
     * @return bool
     */
    public static function existsClient($id)
    {
        return (bool)BackendModel::getContainer()->get('database')->getVar(
            'SELECT 1
             FROM jobs_clients AS i
             WHERE i.id = ? AND i.language = ?
             LIMIT 1',
            array((int)$id, BL::getWorkingLanguage()));
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function existsImage($id)
    {
        return (bool)BackendModel::getContainer()->get('database')->getVar(
            'SELECT 1
             FROM jobs_images AS a
             WHERE a.id = ?',
            array((int)$id)
        );
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function existsFile($id)
    {
        return (bool)BackendModel::getContainer()->get('database')->getVar(
            'SELECT 1
             FROM jobs_files AS a
             WHERE a.id = ?',
            array((int)$id)
        );
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function existsVideo($id)
    {
        return (bool)BackendModel::getContainer()->get('database')->getVar(
            'SELECT 1
             FROM jobs_videos AS a
             WHERE a.id = ?',
            array((int)$id)
        );
    }

    /**
     * Fetch a job
     *
     * @param int $id
     * @return array
     */
    public static function get($id)
    {
        return (array)BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*, m.url, UNIX_TIMESTAMP(i.date) AS date
             FROM jobs AS i
             INNER JOIN meta AS m ON m.id = i.meta_id
             WHERE i.id = ? AND i.language = ?',
            array((int)$id, BL::getWorkingLanguage()));
    }

    /**
     * Get all jobs grouped by categories
     *
     * @return array
     */
    public static function getAllJobsGroupedByCategories()
    {
        $db = BackendModel::getContainer()->get('database');

        $allJobs = (array)$db->getRecords(
            'SELECT p.id, p.title, pc.id AS category_id, pc.title AS category_title
             FROM jobs p
             INNER JOIN jobs_categories pc ON p.category_id = pc.id
             WHERE p.language = ?',
            array(BL::getWorkingLanguage()));

        $jobsGroupedByCategory = array();

        foreach ($allJobs as $pid => $job) {
            $jobsGroupedByCategory[$job['category_title']][$job['id']] = $job['title'];
        }

        //die(print_r($jobsGroupedByCategory));

        return $jobsGroupedByCategory;
    }

    /**
     * Get related jobs of an item
     *
     * @param int $id The job id
     * @return array
     */
    public static function getRelatedJobs($id)
    {
        $db = BackendModel::getContainer()->get('database');

        $relatedJobs = (array)$db->getPairs(
            'SELECT r.related_job_id AS keyId, r.related_job_id AS valueId
             FROM jobs_related r
             WHERE r.job_id = ?',
            array((int)$id));

        // build new keys (starting from zero)
        $i = 0;

        foreach ($relatedJobs as $key => $value) {
            if (isset($relatedJobs[$key])) {
                $relatedJobs[$i] = $relatedJobs[$key];
                unset($relatedJobs[$key]);
            }
            $i++;
        }

        return $relatedJobs;
    }

    /**
     * Fetch an image
     *
     * @param int $id
     * @return array
     */
    public static function getImage($id)
    {
        return (array)BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
             FROM jobs_images AS i
             WHERE i.id = ?',
            array((int)$id));
    }

    /**
     * Fetch an file
     *
     * @param int $id
     * @return array
     */
    public static function getFile($id)
    {
        return (array)BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
             FROM jobs_files AS i
             WHERE i.id = ?',
            array((int)$id));
    }

    /**
     * Fetch an video
     *
     * @param int $id
     * @return array
     */
    public static function getVideo($id)
    {
        return (array)BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
             FROM jobs_videos AS i
             WHERE i.id = ?',
            array((int)$id));
    }

    /**
     * Get all items by a given tag id
     *
     * @param int $tagId
     * @return array
     */
    public static function getByTag($tagId)
    {
        $items = (array)BackendModel::getContainer()->get('database')->getRecords(
            'SELECT i.id AS url, i.title AS name, mt.module
             FROM modules_tags AS mt
             INNER JOIN tags AS t ON mt.tag_id = t.id
             INNER JOIN jobs AS i ON mt.other_id = i.id
             WHERE mt.module = ? AND mt.tag_id = ? AND i.language = ?',
            array('jobs', (int)$tagId, BL::getWorkingLanguage()));

        foreach ($items as &$row) {
            $row['url'] = BackendModel::createURLForAction('edit', 'jobs', null, array('id' => $row['url']));
        }

        return $items;
    }

    /**
     * Get all the categories
     *
     * @param bool [optional] $includeCount
     * @return array
     */
    public static function getCategories($includeCount = false)
    {
        $db = BackendModel::getContainer()->get('database');

        if ($includeCount) {
            return (array)$db->getPairs(
                'SELECT i.id, CONCAT(i.title, " (",  COUNT(p.category_id) ,")") AS title
                 FROM jobs_categories AS i
                 LEFT OUTER JOIN jobs AS p ON i.id = p.category_id AND i.language = p.language
                 WHERE i.language = ?
                 GROUP BY i.id
                 ORDER BY i.sequence',
                array(BL::getWorkingLanguage()));
        }

        return (array)$db->getPairs(
            'SELECT i.id, i.title
             FROM jobs_categories AS i
             WHERE i.language = ?
             ORDER BY i.sequence',
            array(BL::getWorkingLanguage()));
    }

    /**
     * Fetch a category
     *
     * @param int $id
     * @return array
     */
    public static function getCategory($id)
    {
        return (array)BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
             FROM jobs_categories AS i
             WHERE i.id = ? AND i.language = ?',
            array((int)$id, BL::getWorkingLanguage()));
    }

    /**
     * Fetch the category count
     *
     * @return int
     */
    public static function getCategoryCount()
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT COUNT(i.id)
             FROM jobs_categories AS i
             WHERE i.language = ?',
            array(BL::getWorkingLanguage()));
    }

    /**
     * Get all the clients
     *
     * @param bool [optional] $includeCount
     * @return array
     */
    public static function getClients($includeCount = false)
    {
        $db = BackendModel::getContainer()->get('database');

        if ($includeCount) {
            return (array)$db->getPairs(
                'SELECT i.id, CONCAT(i.title, " (",  COUNT(p.category_id) ,")") AS title
                 FROM jobs_clients AS i
                 LEFT OUTER JOIN jobs AS p ON i.id = p.client_id AND i.language = p.language
                 WHERE i.language = ?
                 GROUP BY i.id
                 ORDER BY i.sequence',
                array(BL::getWorkingLanguage()));
        }

        return (array)$db->getPairs(
            'SELECT i.id, i.title
             FROM jobs_clients AS i
             WHERE i.language = ?
             ORDER BY i.sequence',
            array(BL::getWorkingLanguage()));
    }

    /**
     * Fetch a client
     *
     * @param int $id
     * @return array
     */
    public static function getClient($id)
    {
        return (array)BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
             FROM jobs_clients AS i
             WHERE i.id = ? AND i.language = ?',
            array((int)$id, BL::getWorkingLanguage()));
    }

    /**
     * Fetch the client count
     *
     * @return int
     */
    public static function getClientCount()
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT COUNT(i.id)
             FROM jobs_clients AS i
             WHERE i.language = ?',
            array(BL::getWorkingLanguage()));
    }

    /**
     * Fetch the feedback item
     *
     * @param int $id
     * @return array
     */
    public static function getFeedback($id)
    {
        return (array)BackendModel::getContainer()->get('database')->getRecord(
            'SELECT f.*
             FROM jobs_feedback AS f
             WHERE f.id = ?',
            array((int)$id));
    }

    /**
     * Get the maximum sequence for a category
     *
     * @return int
     */
    public static function getMaximumCategorySequence()
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
             FROM jobs_categories AS i
             WHERE i.language = ?',
            array(BL::getWorkingLanguage()));
    }

    /**
     * Get the maximum sequence for a client
     *
     * @return int
     */
    public static function getMaximumClientSequence()
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
             FROM jobs_clients AS i
             WHERE i.language = ?',
            array(BL::getWorkingLanguage()));
    }

    /**
     * Get the max sequence id for a category
     *
     * @param int $id The category id.
     * @return int
     */
    public static function getMaximumSequence($id)
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
             FROM jobs AS i
             WHERE i.category_id = ?',
            array((int)$id));
    }

    /**
     * Get the max sequence id for an image
     *
     * @param int $id The job id.
     * @return int
     */
    public static function getMaximumImagesSequence($id)
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
             FROM jobs_images AS i
             WHERE i.job_id = ?',
            array((int)$id));
    }

    /**
     * Get the max sequence id for an file
     *
     * @param int $id The job id.
     * @return int
     */
    public static function getMaximumFilesSequence($id)
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
             FROM jobs_files AS i
             WHERE i.job_id = ?',
            array((int)$id));
    }

    /**
     * Get the max sequence id for an videos
     *
     * @param int $id The job id.
     * @return int
     */
    public static function getMaximumVideosSequence($id)
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
             FROM jobs_videos AS i
             WHERE i.job_id = ?',
            array((int)$id));
    }

    /**
     * Retrieve the unique URL for an item
     *
     * @param string $url
     * @param int [optional] $id    The id of the item to ignore.
     * @return string
     */
    public static function getURL($url, $id = null)
    {
        $url = \SpoonFilter::urlise((string)$url);
        $db = BackendModel::getContainer()->get('database');

        // new item
        if ($id === null) {
            // already exists
            if ((bool)$db->getVar(
                'SELECT 1
                 FROM jobs AS i
                 INNER JOIN meta AS m ON i.meta_id = m.id
                 WHERE i.language = ? AND m.url = ?
                 LIMIT 1',
                array(BL::getWorkingLanguage(), $url))
            ) {
                $url = BackendModel::addNumber($url);
                return self::getURL($url);
            }
        } // current category should be excluded
        else {
            // already exists
            if ((bool)$db->getVar(
                'SELECT 1
                 FROM jobs AS i
                 INNER JOIN meta AS m ON i.meta_id = m.id
                 WHERE i.language = ? AND m.url = ? AND i.id != ?
                 LIMIT 1',
                array(BL::getWorkingLanguage(), $url, $id))
            ) {
                $url = BackendModel::addNumber($url);
                return self::getURL($url, $id);
            }
        }

        return $url;
    }

    /**
     * Retrieve the unique URL for a category
     *
     * @param string $url
     * @param int [optional] $id The id of the category to ignore.
     * @return string
     */
    public static function getURLForCategory($url, $id = null)
    {
        $url = \SpoonFilter::urlise((string)$url);
        $db = BackendModel::getContainer()->get('database');

        // new category
        if ($id === null) {
            if ((bool)$db->getVar(
                'SELECT 1
                 FROM jobs_categories AS i
                 INNER JOIN meta AS m ON i.meta_id = m.id
                 WHERE i.language = ? AND m.url = ?
                 LIMIT 1',
                array(BL::getWorkingLanguage(), $url))
            ) {
                $url = BackendModel::addNumber($url);
                return self::getURLForCategory($url);
            }
        } // current category should be excluded
        else {
            if ((bool)$db->getVar(
                'SELECT 1
                 FROM jobs_categories AS i
                 INNER JOIN meta AS m ON i.meta_id = m.id
                 WHERE i.language = ? AND m.url = ? AND i.id != ?
                 LIMIT 1',
                array(BL::getWorkingLanguage(), $url, $id))
            ) {
                $url = BackendModel::addNumber($url);
                return self::getURLForCategory($url, $id);
            }
        }

        return $url;
    }

    /**
     * Retrieve the unique URL for a client
     *
     * @param string $url
     * @param int [optional] $id The id of the client to ignore.
     * @return string
     */
    public static function getURLForClient($url, $id = null)
    {
        $url = \SpoonFilter::urlise((string)$url);
        $db = BackendModel::getContainer()->get('database');

        // new client
        if ($id === null) {
            if ((bool)$db->getVar(
                'SELECT 1
                 FROM jobs_clients AS i
                 INNER JOIN meta AS m ON i.meta_id = m.id
                 WHERE i.language = ? AND m.url = ?
                 LIMIT 1',
                array(BL::getWorkingLanguage(), $url))
            ) {
                $url = BackendModel::addNumber($url);
                return self::getURLForClient($url);
            }
        } // current client should be excluded
        else {
            if ((bool)$db->getVar(
                'SELECT 1
                 FROM jobs_clients AS i
                 INNER JOIN meta AS m ON i.meta_id = m.id
                 WHERE i.language = ? AND m.url = ? AND i.id != ?
                 LIMIT 1',
                array(BL::getWorkingLanguage(), $url, $id))
            ) {
                $url = BackendModel::addNumber($url);
                return self::getURLForClient($url, $id);
            }
        }

        return $url;
    }

    /**
     * Insert a question in the database
     *
     * @param array $item
     * @return int
     */
    public static function insert(array $item)
    {
        $insertId = BackendModel::getContainer()->get('database')->insert('jobs', $item);

        BackendModel::invalidateFrontendCache('jobs', BL::getWorkingLanguage());

        return $insertId;
    }

    /**
     * Insert a category in the database
     *
     * @param array $item
     * @param array [optional] $meta The metadata for the category to insert.
     * @return int
     */
    public static function insertCategory(array $item, $meta = null)
    {
        $db = BackendModel::getContainer()->get('database');

        // insert meta
        if ($meta !== null) $item['meta_id'] = $db->insert('meta', $meta);

        // build extra
        $extra = array(
            'module' => 'jobs',
            'type' => 'widget',
            'label' => 'Category',
            'action' => 'category',
            'data' => null,
            'hidden' => 'N',
            'sequence' => $db->getVar(
                    'SELECT MAX(i.sequence) + 1
                     FROM modules_extras AS i
                     WHERE i.module = ?',
                    array('content_blocks')
                )
        );

        if (is_null($extra['sequence'])) $extra['sequence'] = $db->getVar(
            'SELECT CEILING(MAX(i.sequence) / 1000) * 1000
             FROM modules_extras AS i'
        );

        // insert extra
        $item['extra_id'] = $db->insert('modules_extras', $extra);
        $extra['id'] = $item['extra_id'];

        // insert and return the new revision id
        $item['id'] = $db->insert('jobs_categories', $item);

        // update extra (item id is now known)
        $extra['data'] = serialize(array(
                'id' => $item['id'],
                'extra_label' => $item['title'],
                'language' => $item['language'],
                'edit_url' => BackendModel::createURLForAction('edit_category', 'jobs', $item['language']) . '&id=' . $item['id'])
        );

        $db->update(
            'modules_extras',
            $extra,
            'id = ? AND module = ? AND type = ? AND action = ?',
            array($extra['id'], $extra['module'], $extra['type'], $extra['action'])
        );

        BackendModel::invalidateFrontendCache('jobs', BL::getWorkingLanguage());

        return $item['id'];
    }

    /**
     * Insert a client in the database
     *
     * @param array $item
     * @param array [optional] $meta The metadata for the category to insert.
     * @return int
     */
    public static function insertClient(array $item, $meta = null)
    {
        $db = BackendModel::getContainer()->get('database');

        if ($meta !== null) $item['meta_id'] = $db->insert('meta', $meta);
        $item['id'] = $db->insert('jobs_clients', $item);

        BackendModel::invalidateFrontendCache('jobs', BL::getWorkingLanguage());

        return $item['id'];
    }

    /**
     * @param string $item
     * @return int
     */
    private static function insertImage($item)
    {
        return (int)BackendModel::getContainer()->get('database')->insert('jobs_images', $item);
    }

    /**
     * @param string $item
     * @return int
     */
    private static function insertFile($item)
    {
        return (int)BackendModel::getContainer()->get('database')->insert('jobs_files', $item);
    }

    /**
     * @param string $item
     * @return int
     */
    private static function insertVideo($item)
    {
        return (int)BackendModel::getContainer()->get('database')->insert('jobs_videos', $item);
    }

    /**
     * @param string $item
     * @return int
     */
    private static function insertRelatedJobs($item)
    {
        return (int)BackendModel::getContainer()->get('database')->insert('jobs_related', $item);
    }

    /**
     * Update a certain question
     *
     * @param array $item
     */
    public static function update(array $item)
    {
        BackendModel::getContainer()->get('database')->update('jobs', $item, 'id = ?', array((int)$item['id']));
        BackendModel::invalidateFrontendCache('jobs', BL::getWorkingLanguage());
    }

    /**
     * Update a certain category
     *
     * @param array $item
     */
    public static function updateCategory(array $item)
    {
        $db = BackendModel::getContainer()->get('database');

        // build extra
        $extra = array(
            'id' => $item['extra_id'],
            'module' => 'jobs',
            'type' => 'widget',
            'label' => 'Category',
            'action' => 'category',
            'data' => serialize(array(
                    'id' => $item['id'],
                    'extra_label' => $item['title'],
                    'language' => $item['language'],
                    'edit_url' => BackendModel::createURLForAction('edit') . '&id=' . $item['id'])
            ),
            'hidden' => 'N');

        // update extra
        $db->update('modules_extras', $extra, 'id = ? AND module = ? AND type = ? AND action = ?', array($extra['id'], $extra['module'], $extra['type'], $extra['action']));

        // update category
        $db->update('jobs_categories', $item, 'id = ?', array($item['id']));

        BackendModel::invalidateFrontendCache('jobs', BL::getWorkingLanguage());
    }

    /**
     * Update a certain client
     *
     * @param array $item
     */
    public static function updateClient(array $item)
    {
        BackendModel::getContainer()->get('database')->update('jobs_clients', $item, 'id = ?', array($item['id']));
        BackendModel::invalidateFrontendCache('jobs', BL::getWorkingLanguage());
    }

    /**
     * @param array $item
     * @return int
     */
    public static function updateImage(array $item)
    {
        BackendModel::invalidateFrontendCache('jobsCache');
        return (int)BackendModel::getContainer()->get('database')->update(
            'jobs_images',
            $item,
            'id = ?',
            array($item['id'])
        );
    }

    /**
     * @param array $item
     * @return int
     */
    public static function saveImage(array $item)
    {
        if (isset($item['id']) && self::existsImage($item['id'])) {
            self::updateImage($item);
        } else {
            $item['id'] = self::insertImage($item);
        }

        BackendModel::invalidateFrontendCache('jobsCache');
        return (int)$item['id'];
    }

    /**
     * @param array $item
     * @return int
     */
    public static function updateFile(array $item)
    {
        BackendModel::invalidateFrontendCache('jobsCache');
        return (int)BackendModel::getContainer()->get('database')->update(
            'jobs_files',
            $item,
            'id = ?',
            array($item['id'])
        );
    }

    /**
     * @param array $item
     * @return int
     */
    public static function saveFile(array $item)
    {
        if (isset($item['id']) && self::existsFile($item['id'])) {
            self::updateFile($item);
        } else {
            $item['id'] = self::insertFile($item);
        }

        BackendModel::invalidateFrontendCache('jobsCache');
        return (int)$item['id'];
    }

    /**
     * @param array $item
     * @return int
     */
    public static function updateVideo(array $item)
    {
        BackendModel::invalidateFrontendCache('jobsCache');
        return (int)BackendModel::getContainer()->get('database')->update(
            'jobs_videos',
            $item,
            'id = ?',
            array($item['id'])
        );
    }

    /**
     * @param array $item
     * @return int
     */
    public static function saveVideo(array $item)
    {
        if (isset($item['id']) && self::existsVideo($item['id'])) {
            self::updateVideo($item);
        } else {
            $item['id'] = self::insertVideo($item);
        }

        BackendModel::invalidateFrontendCache('jobsCache');
        return (int)$item['id'];
    }

    /**
     *
     * @param int $jobId The id of the item where to assign the related jobs.
     * @param array $relatedJobs The related jobs for the item.
     * @param array [optional] $oRelatedJobs The related jobs already existing for the item. If not provided a new record will be created.
     *
     * @return int
     */
    public static function saveRelatedJobs($jobId, $relatedJobs, $oRelatedJobs = null)
    {
        $item['job_id'] = $jobId;

        if (isset($oRelatedJobs)) {
            // Insert new records
            $newRelatedJobs = array_diff($relatedJobs, $oRelatedJobs);
            foreach ($newRelatedJobs AS $key => $newRelatedJobs) {
                $item['related_job_id'] = $newRelatedJobs;
                self::insertRelatedJobs($item);
            }

            // Delete old records
            $oldRelatedJobs = array_diff($oRelatedJobs, $relatedJobs);
            foreach ($oldRelatedJobs AS $key => $oldRelatedJobs) {
                $item['related_job_id'] = $oldRelatedJobs;
                self::deleteRelatedJobs($item['job_id'], $item['related_job_id']);
            }
        } else {
            // Insert new records
            foreach ($relatedJobs AS $key => $relatedJobs) {
                $item['related_job_id'] = $relatedJobs;
                self::insertRelatedJobs($item);
            }
        }
    }

    /**
     * @param array $item
     * @return int
     */
    public static function updateRelatedJobs(array $item)
    {
        return (int)BackendModel::getContainer()->get('database')->update(
            'jobs_related',
            $item,
            array()
        );
    }
}
