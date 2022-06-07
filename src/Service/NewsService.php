<?php


namespace App\Service;

use Exception;
use DateTime;  
use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;

class NewsService
{
   
  
     /**
     * Number of random events to display 
     */
    const random_News_Value = 6; 

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

     /**     
     * @var NewsRepository 
     */
    private NewsRepository $newsRepository;   

    /**   
     * @param NewsRepository $newsRepository
     */
    public function __construct(NewsRepository $newsRepository, EntityManagerInterface $entityManager)
    {
        $this->newsRepository = $newsRepository; 
        $this->entityManager = $entityManager;
    }

       

    /**
     * @param News[] $news
     * @param News[] $result  
     * @return News[]      
     */ 
    public function reOrderTable(array $news, array $result){   
           
       $array_dateTimes =  array(); 
  
       for($i = 0; $i< count($news); $i++){
     
           $val1 = DateTime::createFromInterface($news[$i]->getPublishedAt());  
           $array_dateTimes[$i] = $val1->format('Y-m-d H:i:s');
              
       }

       usort($array_dateTimes, function($time1, $time2){
                 
            if (strtotime($time1) < strtotime($time2))
               return 1;
           
           else if (strtotime($time1) > strtotime($time2)) 
               return -1;
           else
               return 0;
       });

    
       usort($array_dateTimes, function($time1, $time2){
                 
            $hours1 = substr($time1, 11, -6);
            $minutes1 = substr($time1[0], 14, -3); 

            $hours2 = substr($time2, 11, -6);
            $minutes2 = substr($time2[0], 14, -3); 

            if( intval($hours1) <  intval($hours2) )
            {
                return 1; 
            }
            else if ( intval($hours1) >  intval($hours2) ){
                return -1;   
            }
            else{
                if( intval($minutes1) <  intval($minutes2) )
                {
                    return 1; 
                }
                else if( intval($minutes1) >  intval($minutes2) ){
                     return -1;    
                }
                else{
                    return 0; 
                }

            }
           
      });
         
   foreach( $array_dateTimes as $date){

        foreach( $news as $new){
 
            $dateTime = DateTime::createFromInterface($new->getPublishedAt())->format('Y-m-d H:i:s'); 

                    if( strcmp($dateTime, $date) == 0 ){  
        
                        if (!in_array( $new,  $result)){

                            array_push( $result, $new); 
                        }
            
                    }   
                        
                }    
        }   
      
        return $result; 
    }


    
    /**
    * @return News[]   
     */ 
    public function randomNewsList() 
    {     
    
       $news = array(); 
       $result = array();    
    
       $allNews = $this->newsRepository->allNews();

       $rangeMax = count($allNews) - 1;  
 
       if( count($allNews) > self::random_News_Value ){
          
        # Let's generate 6 Random events 
        while( count($news) !=  self::random_News_Value ){

            for($i=0; $i< count($allNews); $i++){
                
                $forRandomNews = mt_rand(0,  $rangeMax);       
        
                    if( $i == $forRandomNews){
        
                            if( !in_array( $allNews[$i], $news ) ){
            
                                array_push($news, $allNews[$i]); 
                                
                            } 
                    }            
            }   
                
        }

        return $this->reOrderTable($news, $result); 

       }
   
       return $this->reOrderTable($allNews, $result); 
   

    }    
    
    /**   
     * @param News $news
     * @return News 
     * @throws Exception
     */
    public function updatePicture(News $news)
    {

        if (!$news->getId()) {
            throw new Exception('The user should have an id for updating');
        }
        if (!$this->newsRepository->find($news->getId())) {
            throw new Exception('The user should have an id for updating');
   
        }  
        
        $this->entityManager->persist($news);
        $this->entityManager->flush();
        return $news;       


    }

  
   
}