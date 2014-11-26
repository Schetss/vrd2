<?php

namespace Backend\Modules\Immo\Engine;

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
 * In this file we store all generic functions that we will be using in the immo module
 *
 * @author Bart De Clercq <info@lexxweb.be>
 * @author Tim van Wolfswinkel <tim@reclame-mediabureau.nl>
 */
class Model
{
    const QRY_DATAGRID_BROWSE =
        'SELECT i.id, i.category_id, i.title, p.title AS client, i.client_id, i.hidden, i.sequence
         FROM immo AS i
         LEFT OUTER JOIN immo_clients AS p ON p.id = i.client_id
         WHERE i.language = ? AND i.category_id = ?
         ORDER BY i.sequence ASC';

    const QRY_DATAGRID_BROWSE_CATEGORIES =
        'SELECT i.id, i.title, COUNT(p.id) AS num_items, i.sequence
         FROM immo_categories AS i
         LEFT OUTER JOIN immo AS p ON i.id = p.category_id AND p.language = i.language
         WHERE i.language = ?
         GROUP BY i.id
         ORDER BY i.sequence ASC';

    const QRY_DATAGRID_BROWSE_CLIENTS =
        'SELECT i.id, i.title, i.sequence
         FROM immo_clients AS i
         WHERE i.language = ?
         GROUP BY i.id
         ORDER BY i.sequence ASC';

    const QRY_DATAGRID_BROWSE_IMAGES =
        'SELECT i.id, i.immo_id, i.filename, i.title, i.sequence
         FROM immo_images AS i
         WHERE i.immo_id = ?
         GROUP BY i.id';

    const QRY_DATAGRID_BROWSE_FILES =
        'SELECT i.id, i.immo_id, i.filename, i.title, i.sequence
         FROM immo_files AS i
         WHERE i.immo_id = ?
         GROUP BY i.id';

    const QRY_DATAGRID_BROWSE_VIDEOS =
        'SELECT i.id, i.immo_id, i.embedded_url, i.title, i.sequence
         FROM immo_videos AS i
         WHERE i.immo_id = ?
         GROUP BY i.id';

    /**
     * Delete a question
     *
     * @param int $id
     */
    public static function delete($id)
    {
        $id = (int)$id;
        $immoFilesPath = FRONTEND_FILES_PATH . '/immo/' . $id;
        \SpoonDirectory::delete($immoFilesPath);
        $immo = self::get($id);
        if (!empty($immo)) {
            $database = BackendModel::getContainer()->get('database');
            $database->delete('meta', 'id = ?', array((int)$immo['meta_id']));
            $database->delete('immo_related', 'immo_id = ? OR related_immo_id = ?', array($id, $id));
            $database->delete('immo_images', 'immo_id = ?', array($id));
            $database->delete('immo_videos', 'immo_id = ?', array($id));
            $database->delete('immo_files', 'immo_id = ?', array($id));
            $database->delete('immo', 'id = ?', array($id));
            BackendTagsModel::saveTags($id, '', 'immo');
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
            'module' => 'immo',
            'type' => 'widget',
            'action' => 'category');

        // delete extra
        $db->delete('modules_extras', 'id = ? AND module = ? AND type = ? AND action = ?', array($extra['id'], $extra['module'], $extra['type'], $extra['action']));

        // delete blocks with this item linked
        $db->delete('pages_blocks', 'extra_id = ?', array($item['extra_id']));

        if (!empty($item)) {
            $db->delete('meta', 'id = ?', array($item['meta_id']));
            $db->delete('immo_categories', 'id = ?', array((int)$id));
            $db->update('immo', array('category_id' => null), 'category_id = ?', array($id));

            // invalidate the cache for the immo
            BackendModel::invalidateFrontendCache('immo', BL::getWorkingLanguage());
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
                 FROM immo AS i
                 WHERE i.category_id = ? AND i.language = ?
                 LIMIT 1',
                array((int)$id, BL::getWorkingLanguage())) == 0);

        // exception
        if (!BackendModel::getModuleSetting('immo', 'allow_multiple_categories', true) && self::getCategoryCount() == 1) {
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
            $db->delete('immo_clients', 'id = ?', array((int)$id));
            $db->update('immo', array('client_id' => null), 'client_id = ?', array((int)$id));

            // invalidate the cache for the immo
            BackendModel::invalidateFrontendCache('Immo', BL::getWorkingLanguage());
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
                 FROM immo AS i
                 WHERE i.client_id = ? AND i.language = ?
                 LIMIT 1',
                array((int)$id, BL::getWorkingLanguage())) == 0);

        // exception
        if (!BackendModel::getModuleSetting('immo', 'allow_multiple_clients', true) && self::getClientCount() == 1) {
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
            $immo = self::get($item['immo_id']);

            // delete image reference from db
            BackendModel::getContainer()->get('database')->delete('immo_images', 'id = ?', array($id));

            // delete image from disk
            $basePath = FRONTEND_FILES_PATH . '/immo/' . $item['immo_id'];
            \SpoonFile::delete($basePath . '/source/' . $item['filename']);
            \SpoonFile::delete($basePath . '/64x64/' . $item['filename']);
            \SpoonFile::delete($basePath . '/128x128/' . $item['filename']);
            \SpoonFile::delete($basePath . '/' . BackendModel::getModuleSetting('immo', 'width1') . 'x' . BackendModel::getModuleSetting('immo', 'height1') . '/' . $item['filename']);
            \SpoonFile::delete($basePath . '/' . BackendModel::getModuleSetting('immo', 'width2') . 'x' . BackendModel::getModuleSetting('immo', 'height2') . '/' . $item['filename']);
            \SpoonFile::delete($basePath . '/' . BackendModel::getModuleSetting('immo', 'width3') . 'x' . BackendModel::getModuleSetting('immo', 'height3') . '/' . $item['filename']);
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
            $immo = self::get($item['immo_id']);

            // delete file reference from db
            BackendModel::getContainer()->get('database')->delete('immo_files', 'id = ?', array($id));

            // delete file from disk
            $basePath = FRONTEND_FILES_PATH . '/immo/' . $item['immo_id'];
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
            $immo = self::get($item['immo_id']);

            // delete video reference from db
            BackendModel::getContainer()->get('database')->delete('immo_videos', 'id = ?', array($id));
        }
    }

    /**
     * Delete related immo
     *
     * @param int The immo id
     * @param int The related immo id
     */
    public static function deleteRelatedImmo($immoId, $relatedImmoId = null)
    {
        if (isset($relatedImmoId)) {
            BackendModel::getContainer()->get('database')->delete('immo_related', 'immo_id = ? AND related_immo_id = ?', array((int)$immoId, (int)$relatedImmoId));
        } else {
            BackendModel::getContainer()->get('database')->delete('immo_related', 'immo_id = ?', array((int)$immoId));
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
              FROM immo AS i
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
             FROM immo_categories AS i
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
             FROM immo_clients AS i
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
             FROM immo_images AS a
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
             FROM immo_files AS a
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
             FROM immo_videos AS a
             WHERE a.id = ?',
            array((int)$id)
        );
    }

    /**
     * Fetch a immo
     *
     * @param int $id
     * @return array
     */
    public static function get($id)
    {
        return (array)BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*, m.url, UNIX_TIMESTAMP(i.date) AS date
             FROM immo AS i
             INNER JOIN meta AS m ON m.id = i.meta_id
             WHERE i.id = ? AND i.language = ?',
            array((int)$id, BL::getWorkingLanguage()));
    }

    /**
     * Get all immo grouped by categories
     *
     * @return array
     */
    public static function getAllImmoGroupedByCategories()
    {
        $db = BackendModel::getContainer()->get('database');

        $allImmo = (array)$db->getRecords(
            'SELECT p.id, p.title, pc.id AS category_id, pc.title AS category_title
             FROM immo p
             INNER JOIN immo_categories pc ON p.category_id = pc.id
             WHERE p.language = ?',
            array(BL::getWorkingLanguage()));

        $immoGroupedByCategory = array();

        foreach ($allImmo as $pid => $immo) {
            $immoGroupedByCategory[$immo['category_title']][$immo['id']] = $immo['title'];
        }

        //die(print_r($immoGroupedByCategory));

        return $immoGroupedByCategory;
    }

    /**
     * Get related immo of an item
     *
     * @param int $id The immo id
     * @return array
     */
    public static function getRelatedImmo($id)
    {
        $db = BackendModel::getContainer()->get('database');

        $relatedImmo = (array)$db->getPairs(
            'SELECT r.related_immo_id AS keyId, r.related_immo_id AS valueId
             FROM immo_related r
             WHERE r.immo_id = ?',
            array((int)$id));

        // build new keys (starting from zero)
        $i = 0;

        foreach ($relatedImmo as $key => $value) {
            if (isset($relatedImmo[$key])) {
                $relatedImmo[$i] = $relatedImmo[$key];
                unset($relatedImmo[$key]);
            }
            $i++;
        }

        return $relatedImmo;
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
             FROM immo_images AS i
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
             FROM immo_files AS i
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
             FROM immo_videos AS i
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
             INNER JOIN immo AS i ON mt.other_id = i.id
             WHERE mt.module = ? AND mt.tag_id = ? AND i.language = ?',
            array('immo', (int)$tagId, BL::getWorkingLanguage()));

        foreach ($items as &$row) {
            $row['url'] = BackendModel::createURLForAction('edit', 'immo', null, array('id' => $row['url']));
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
                 FROM immo_categories AS i
                 LEFT OUTER JOIN immo AS p ON i.id = p.category_id AND i.language = p.language
                 WHERE i.language = ?
                 GROUP BY i.id
                 ORDER BY i.sequence',
                array(BL::getWorkingLanguage()));
        }

        return (array)$db->getPairs(
            'SELECT i.id, i.title
             FROM immo_categories AS i
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
             FROM immo_categories AS i
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
             FROM immo_categories AS i
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
                 FROM immo_clients AS i
                 LEFT OUTER JOIN immo AS p ON i.id = p.client_id AND i.language = p.language
                 WHERE i.language = ?
                 GROUP BY i.id
                 ORDER BY i.sequence',
                array(BL::getWorkingLanguage()));
        }

        return (array)$db->getPairs(
            'SELECT i.id, i.title
             FROM immo_clients AS i
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
             FROM immo_clients AS i
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
             FROM immo_clients AS i
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
             FROM immo_feedback AS f
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
             FROM immo_categories AS i
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
             FROM immo_clients AS i
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
             FROM immo AS i
             WHERE i.category_id = ?',
            array((int)$id));
    }

    /**
     * Get the max sequence id for an image
     *
     * @param int $id The immo id.
     * @return int
     */
    public static function getMaximumImagesSequence($id)
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
             FROM immo_images AS i
             WHERE i.immo_id = ?',
            array((int)$id));
    }

    /**
     * Get the max sequence id for an file
     *
     * @param int $id The immo id.
     * @return int
     */
    public static function getMaximumFilesSequence($id)
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
             FROM immo_files AS i
             WHERE i.immo_id = ?',
            array((int)$id));
    }

    /**
     * Get the max sequence id for an videos
     *
     * @param int $id The immo id.
     * @return int
     */
    public static function getMaximumVideosSequence($id)
    {
        return (int)BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
             FROM immo_videos AS i
             WHERE i.immo_id = ?',
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
                 FROM immo AS i
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
                 FROM immo AS i
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
                 FROM immo_categories AS i
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
                 FROM immo_categories AS i
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
                 FROM immo_clients AS i
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
                 FROM immo_clients AS i
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
        $insertId = BackendModel::getContainer()->get('database')->insert('immo', $item);

        BackendModel::invalidateFrontendCache('immo', BL::getWorkingLanguage());

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
            'module' => 'immo',
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
        $item['id'] = $db->insert('immo_categories', $item);

        // update extra (item id is now known)
        $extra['data'] = serialize(array(
                'id' => $item['id'],
                'extra_label' => $item['title'],
                'language' => $item['language'],
                'edit_url' => BackendModel::createURLForAction('edit_category', 'immo', $item['language']) . '&id=' . $item['id'])
        );

        $db->update(
            'modules_extras',
            $extra,
            'id = ? AND module = ? AND type = ? AND action = ?',
            array($extra['id'], $extra['module'], $extra['type'], $extra['action'])
        );

        BackendModel::invalidateFrontendCache('immo', BL::getWorkingLanguage());

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
        $item['id'] = $db->insert('immo_clients', $item);

        BackendModel::invalidateFrontendCache('immo', BL::getWorkingLanguage());

        return $item['id'];
    }

    /**
     * @param string $item
     * @return int
     */
    private static function insertImage($item)
    {
        return (int)BackendModel::getContainer()->get('database')->insert('immo_images', $item);
    }

    /**
     * @param string $item
     * @return int
     */
    private static function insertFile($item)
    {
        return (int)BackendModel::getContainer()->get('database')->insert('immo_files', $item);
    }

    /**
     * @param string $item
     * @return int
     */
    private static function insertVideo($item)
    {
        return (int)BackendModel::getContainer()->get('database')->insert('immo_videos', $item);
    }

    /**
     * @param string $item
     * @return int
     */
    private static function insertRelatedImmo($item)
    {
        return (int)BackendModel::getContainer()->get('database')->insert('immo_related', $item);
    }

    /**
     * Update a certain question
     *
     * @param array $item
     */
    public static function update(array $item)
    {
        BackendModel::getContainer()->get('database')->update('immo', $item, 'id = ?', array((int)$item['id']));
        BackendModel::invalidateFrontendCache('immo', BL::getWorkingLanguage());
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
            'module' => 'immo',
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
        $db->update('immo_categories', $item, 'id = ?', array($item['id']));

        BackendModel::invalidateFrontendCache('immo', BL::getWorkingLanguage());
    }

    /**
     * Update a certain client
     *
     * @param array $item
     */
    public static function updateClient(array $item)
    {
        BackendModel::getContainer()->get('database')->update('immo_clients', $item, 'id = ?', array($item['id']));
        BackendModel::invalidateFrontendCache('immo', BL::getWorkingLanguage());
    }

    /**
     * @param array $item
     * @return int
     */
    public static function updateImage(array $item)
    {
        BackendModel::invalidateFrontendCache('immoCache');
        return (int)BackendModel::getContainer()->get('database')->update(
            'immo_images',
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

        BackendModel::invalidateFrontendCache('immoCache');
        return (int)$item['id'];
    }

    /**
     * @param array $item
     * @return int
     */
    public static function updateFile(array $item)
    {
        BackendModel::invalidateFrontendCache('immoCache');
        return (int)BackendModel::getContainer()->get('database')->update(
            'immo_files',
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

        BackendModel::invalidateFrontendCache('immoCache');
        return (int)$item['id'];
    }

    /**
     * @param array $item
     * @return int
     */
    public static function updateVideo(array $item)
    {
        BackendModel::invalidateFrontendCache('immoCache');
        return (int)BackendModel::getContainer()->get('database')->update(
            'immo_videos',
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

        BackendModel::invalidateFrontendCache('immoCache');
        return (int)$item['id'];
    }

    /**
     *
     * @param int $immoId The id of the item where to assign the related immo.
     * @param array $relatedImmo The related immo for the item.
     * @param array [optional] $oRelatedImmo The related immo already existing for the item. If not provided a new record will be created.
     *
     * @return int
     */
    public static function saveRelatedImmo($immoId, $relatedImmo, $oRelatedImmo = null)
    {
        $item['immo_id'] = $immoId;

        if (isset($oRelatedImmo)) {
            // Insert new records
            $newRelatedImmo = array_diff($relatedImmo, $oRelatedImmo);
            foreach ($newRelatedImmo AS $key => $newRelatedImmo) {
                $item['related_immo_id'] = $newRelatedImmo;
                self::insertRelatedImmo($item);
            }

            // Delete old records
            $oldRelatedImmo = array_diff($oRelatedImmo, $relatedImmo);
            foreach ($oldRelatedImmo AS $key => $oldRelatedImmo) {
                $item['related_immo_id'] = $oldRelatedImmo;
                self::deleteRelatedImmo($item['immo_id'], $item['related_immo_id']);
            }
        } else {
            // Insert new records
            foreach ($relatedImmo AS $key => $relatedImmo) {
                $item['related_immo_id'] = $relatedImmo;
                self::insertRelatedImmo($item);
            }
        }
    }

    /**
     * @param array $item
     * @return int
     */
    public static function updateRelatedImmo(array $item)
    {
        return (int)BackendModel::getContainer()->get('database')->update(
            'immo_related',
            $item,
            array()
        );
    }
}
