<?php 

namespace App\Service; 

use Dompdf\Options;
    
use Dompdf\Dompdf;
 

class PdfService {
    

    /**
     * @var Dompdf
    */
    private Dompdf $domPdf;




    public function __construct()
    {      

        $this->domPdf = new Dompdf();  
       
        $pdfOptions = new Options();  

        $pdfOptions->set('defaultFont', 'Garamond');

        $this->domPdf->setOptions($pdfOptions); 
             
    }   


    # We gonna commit the code with --no-verify option when the param type of this function will be the only error  

     /**
      *@param string $html 
     * @return void    
     */     
    public function showPdfFile(string $html)   
    {

        # attachement is to display the pdf on the browser 
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->stream("test.pdf", [
            'Attachement' => false 
        ]); 
   
    }

    

    #When we want just the binary file to attach it to an email for example and not display the pdf 
    
     /**
      * @param string $html 
     * @return void   
     */     
    public function generateBinaryPdf(string $html)
    {

        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->output(); 

    }


    
     




}