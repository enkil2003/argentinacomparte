<?php
/**
 * News
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class News extends BaseNews
{
    public static function findById($id,$active = NULL)
    {
        $q = Doctrine_Query::create()
            ->select('
                n.id,
                n.title,
                IF(
                    LENGTH(n.copy) > 200,
                    CONCAT(LEFT(n.copy, 280), \'...</p>\'),
                    n.copy
                ) AS copy,
                n.modified_by,
                n.creation_date,
                n.modification_date,
                n.preferential_category,
                n.news_id,
                n.youtube,
                n.body,
                n.active,
                nhc.*,
                n2.*,
                i2.*,
                i.*,
                g.*
           ')
            ->from('News n')
            ->leftJoin('n.Images i')
            ->leftJoin('n.News n2')
            ->leftJoin('n2.Images i2')
            ->leftJoin('n.NewsHasCategory nhc')
            ->leftJoin('n.Geolocalization g')
            ->where('n.id = ?', $id);
 //           if ($active) {
 //               $q->andwhere('n.active = ?', $active);
  //          }
            ;
            $q->limit(1);
        if ($result = $q->fetchOne()) {
            $return = $result->toArray();
            return $return;
        }
    }
    
    public function getAllNewsFromCategory($id) {
        $q = $this->_getNewsFromCategoryQuery($id);
        if ($result = $q->fetchArray()) {
            return $result;
        }
        return array();
    }
    
    /**
     * Removes a news from category
     * @param int $id
     * @return void
     */
    private static function _removeNewsFromCategory($id) {
        $q = new Doctrine_Query();
        $q->delete('NewsHasCategory')
            ->where("news_id = ?", $id)
            ->execute();
    }
    
    /**
     * Removes child news from public politic
     * @param array $news public politics array
     * @return void
     */
    private static function _removeChildNews($news) {
        if (isset($news['News']) && count($news['News'])) {
            foreach($news['News'] as $childNews) {
                self::deleteById($childNews['id']);
            }
        }
    }
    
    /**
     * Removes images from news or public politic
     * @param array $news public politic or news
     * @return void
     */
    private static function _removeImagesFromNews($news) {
        if (isset($news['Images']) && count($news['Images'])) {
            $folder = APPLICATION_TMP_DIR . '/' . $news['id'];
            foreach ($news['Images'] as $image) {
                $file =  $folder . '/' . $image['name'];
                if (file_exists($file)) {
                    unlink($file);
                }
                $q = new Doctrine_Query();
                $q->delete('Images')
                    ->where("id = ?", $image['id'])
                    ->execute();
            }
        }
    }
    
    /**
     * Removes new from category, child news and related images
     * @param int $id news id
     * @return void
     */
    public static function deleteById($id)
    {
        $news = self::findById($id);
        self::_removeNewsFromCategory($id);
        self::_removeChildNews($news);
        self::_removeImagesFromNews($news);
        
        $folder = APPLICATION_TMP_DIR . '/' . $news['id'];
        if (file_exists($folder)) {
            rmdir($folder);
        }
        
        $q = new Doctrine_Query();
        $q->delete('News')
            ->where("id = ?", $id)
            ->execute();
   
    }
    
    
    /**
     * Removes new from category, child news and related images
     * @param int $id news id
     * @return void
     */
public static function TrashById($id)
    {

    	$q = Doctrine_Query::create()
    	->update('News')
    	->set('active', '-1')
    	->where('id = ?', $id)
    	->execute();
    	
    	
    	$q2 = Doctrine_Query::create()
    	->update('News')
    	->set('active', '-1')
    	->where('news_id = ?', $id)
    	->execute();
    }
    

    
    /**
     * Enter description here ...
     * @param unknown_type $id
     * @return Doctrine_Query
     */
    private function _getNewsFromCategoryQuery($id, $limit = null)
    {
        $q = Doctrine_Query::create()
            ->select('
                n.id,
                n.title,
                n.mintit,
                IF(
                    LENGTH(n.copy) > 200,
                    CONCAT(LEFT(n.copy, 280), \'...</p>\'),
                    n.copy
                ) AS copy,
                n.modified_by,
                n.creation_date,
                n.modification_date,
                n.preferential_category,
                n.news_id,
                n.youtube,
                i.*,
                n2.*,
                i2.*
           ')
            ->from('News n')
            ->innerJoin('n.NewsHasCategory nhc')
            ->innerJoin('nhc.Category c')
            ->innerJoin('n.Images i')
            ->leftJoin('n.News n2')
            ->leftJoin('n2.Images i2')
            ->orderBy('n.creation_date DESC, id DESC')
            ->where('c.id = ?', $id)
            ->andWhere('i.highlight = 1')
            ->andwhere('n.active = 1')
            ;
        
        if (NULL !== $limit) {
            $q->limit($limit);
        }
        return $q;
    }
    
    private function _getChildsFromCategoryQuery($id, $limit = null, $not=null)
    {
        $q = Doctrine_Query::create()
            ->select('
                n.id,
                n.title,
                n.mintit,
                IF(
                    LENGTH(n.copy) > 200,
                    CONCAT(LEFT(n.copy, 280), \'...</p>\'),
                    n.copy
                ) AS copy,
                n.modified_by,
                n.creation_date,
                n.modification_date,
                n.preferential_category,
                n.news_id,
                n.youtube,
                i.*,
            ')
            ->from('News n')
                //->innerJoin('n.NewsHasCategory nhc')
                //->innerJoin('nhc.Category c')
            ->innerJoin('n.Images i')
            ->innerJoin('n.News n2 ON n.News_id = n2.id')
                //->leftJoin('n.News n2')
                //->leftJoin('n2.Images i2')
            ->orderBy('n.creation_date DESC, id DESC')
                //->where('c.id = ?', $id)
            ->where('n.News_id is not null')
            ->andWhere('n2.preferential_category = ?',$id)
            ->andWhere('i.highlight = 1')
            ->andwhere('n.active = 1')
        ;
        if (NULL !== $not) {
            $q->andWhere('n.id <> ?', $not);
        }
        if (NULL !== $limit) {
            $q->limit($limit);
        }
        return $q;
    }
    
    private function _getAllPpFromCategoryQuery($id, $limit = null)
    {
        $q = Doctrine_Query::create()
        ->select('
                n.id,
                n.title,
                n.mintit,
                IF(
                LENGTH(n.copy) > 200,
                CONCAT(LEFT(n.copy, 280), \'...</p>\'),
                n.copy
        ) AS copy,
                n.modified_by,
                n.creation_date,
                n.modification_date,
                n.preferential_category,
                n.news_id,
                n.youtube,
                i.*,
                 
                ')
                ->from('News n')
                //->innerJoin('n.NewsHasCategory nhc')
        //->innerJoin('nhc.Category c')
        ->innerJoin('n.Images i')
        //->innerJoin('n.News n2 ON n.News_id = n2.id')
        //->leftJoin('n.News n2')
        //->leftJoin('n2.Images i2')
        ->orderBy('RANDOM()')
        //->where('c.id = ?', $id)
        ->where('n.News_id is null')
        ->andWhere('n.preferential_category = ?',$id)
        ->andWhere('i.highlight = 1')
        ->andwhere('n.active = 1')
        ;
        if (NULL !== $limit) {
            $q->limit($limit);
        }
        return $q;
    }
    
    /**
     * Returns all news from a category id.
     * @param int $id
     * @return array
     */
    public function getChildsNewsFromCategory($id, $limit=NULL, $not = NULL)
    {
        $q = $this->_getChildsFromCategoryQuery($id, $limit, $not);
        if ($result = $q->fetchArray()) {
            return $result;
        }
        return array();
    }
    
    public function getPublicNewsFromPp($id)
    {
        $q = Doctrine_Query::create()
        ->select('n.*, i.*')
        ->from('News n')
        ->innerJoin('n.Images i')
        ->where('n.news_id = ?', $id)
        ->andwhere('n.active > 0')
        ->orderBy('n.creation_date DESC, n.id DESC')
        ->limit(5)
        ;
        	if ($result = $q->fetchArray()) {
    		return $result;
    	}
    }
    /**
     * Returns all Pp from a category id.
     * @param int $id
     * @return array
     */
    public function getAllPpFromCategory($id)
    {
        $q = $this->_getAllPpFromCategoryQuery($id);
        if ($result = $q->fetchArray()) {
          return $result;
        }
        return array();
    }
    
    /**
     * Returns last news from a category id.
     * @param int $id
     * @return array
     */
    public function getNewsFromCategory($id)
    {
        $q = $this->_getNewsFromCategoryQuery($id, 1);
        if ($result = $q->fetchOne()) {
            $return = $result->toArray();
            return $return;
        }
    }
    
    public static function getNewsFromId($id)
    {
        $q = Doctrine_Query::create()
        ->select('n.*, i.*')
        ->from('News n')
        ->innerJoin('n.Images i')
        ->where('n.id = ?', $id)
        ;
        if ($result = $q->fetchOne()) {
            $return = $result->toArray();
            return $return;
        }
    }
    public static function getActiveFromId($id)
    {
    	$q = Doctrine_Query::create()
    	->select('n.*')
    	->from('News n')
    	->where('n.id = ?', $id)
    	->andwhere('n.active = 1')
    	;
    	if ($result = $q->fetchOne()) {
    		$return = $result->toArray();
    		return $return;
    	}
    }
    public static function getLatestNews($butId = NULL)
    {
        $q = Doctrine_Query::create()
            ->select('n.*, i.*')
            ->from('News n')
            ->innerJoin('n.Images i')
            ->innerJoin('n.News n2 ON n.News_id = n2.id')
            ->where('n.news_id IS NOT NULL')
            ->andWhere('i.highlight = 1')
            ->andWhere('n.active = 1')
            ->andWhere('n2.active = 1') // descarta las noticias cuya pp no está activa en la home
            ->orderBy('n.creation_date DESC, n.id DESC')
            ->limit(5);
        
        if (NULL !== $butId) {
            $q->andWhere('n.id != ?', $butId);
        }
        if ($result = $q->fetchArray()) {
            return $result;
        }
    }
    
    public static function getLatestHomeNews($butId = NULL)
    {
    	$q = Doctrine_Query::create()
    	->select('n.*, i.*')
    	->from('News n')
    	->innerJoin('n.Images i')
    	->innerJoin('n.News n2 ON n.News_id = n2.id')
    	->where('n.news_id IS NOT NULL')
    	->andWhere('i.highlight = 1')
    	->andWhere('n.active = 1')
    	->andWhere('n2.active != 0')->andWhere('n2.active != -1') // incluye las noticias de TEMAS para la home
    	->orderBy('n.creation_date DESC, n.id DESC')
    	->limit(5);
    
    	if (NULL !== $butId) {
    		$q->andWhere('n.id != ?', $butId);
    	}
    	if ($result = $q->fetchArray()) {
    		return $result;
    	}
    }
    
    public static function getPublicPolitics()
    {
        $q = Doctrine_Query::create()
            ->select('n.*')
            ->from('News n')
            ->where('n.news_id IS NULL')
            ->andwhere('n.active = 1')
            ->orderBy('n.title ASC')
        ;
        
        if ($result = $q->fetchArray()) {
            return $result;
        }
    }
    
    // para que aparezcan las pp en estado borrador cuando se crea noticia
    // también para que aparezca el estado -2 que es la pp que TEMAS
    // no llama a la eliminadas estado -1
    public static function getAllPublicPolitics()
    {
    	$q = Doctrine_Query::create()
    	->select('n.*')
    	->from('News n')
    	->where('n.news_id IS NULL')
    	->andwhere('n.active != -1')
    	->orderBy('n.title ASC')
    	;
    
    	if ($result = $q->fetchArray()) {
    		return $result;
    	}
    }
    
    public static function listPublicPolitics()
    {
    	$q = Doctrine_Query::create()
    	->select('n.*, c.name','n2.title')
    	->from('News n')
     	->innerJoin('n.Category c ON n.preferential_category = c.id')
     //	->leftJoin('n.News n2 ON n.id = n2.news_id')
    	->where('n.news_id IS NULL')
    	->andwhere('n.active >= 0')
        ->orderBy('n.creation_date DESC, n.id DESC')
    	;
    
    	if ($result = $q->fetchArray()) {
    		return $result;
    	}
    }
    
    public static function listNews()
    {
    	$q = Doctrine_Query::create()
    	->select('n.*, n2.title')
    	->from('News n')
    	->innerJoin('n.News n2 ON n.News_id = n2.id')
    	->where('n.news_id IS NOT NULL')
    	->andwhere('n.active >= 0')
    	->orderBy('n.creation_date DESC, n.id DESC')
    	;
    
    	if ($result = $q->fetchArray()) {
    		return $result;
    	}
    }
    
    public static function getPublicPoliticsRandom($limit = null, $butId = NULL)
    {
        $q = Doctrine_Query::create()
        ->select('n.*, i.*')
        ->from('News n')
        ->innerJoin('n.Images i')
        ->where('n.news_id IS NULL')
        ->andWhere('i.highlight = 1')
        ->andwhere('n.active = 1')
        ->orderBy('RANDOM()')
        ;
        if (NULL !== $butId) {
            $q->andWhere('n.id != ?', $butId);
        }
        if (NULL !== $limit) {
            $q->limit($limit);
        } 
        if ($result = $q->fetchArray()) {
            return $result;
        }
    }
}