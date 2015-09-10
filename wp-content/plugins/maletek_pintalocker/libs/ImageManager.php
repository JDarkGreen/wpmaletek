<?php
/**
* Clase que crea una copia de una imagen, de un tama�o distinto, a trav�s de distintos m�todos
* Ejemplo de uso:
* <code>
* $o=new ImageResize($imagen_origen);
* $o->resizeWidth(100);
* $o->save($imagen_destino);
* </code>
* TODO: 
* - Definir de manera autom�tica el formato de salida.
* - Definir otros tipos de formato de entrada, aparte de gif, jpg y png
*/
class ImageManager {
    private $gd_s;
    private $source;
    private $gd_d;
    private $imagen_info;
    private $width_s;
    private $height_s;
    private $width_d;
    private $height_d;
    private $force_resize=false;
    private $aCreateFunctions = array(
        IMAGETYPE_GIF=>'imagecreatefromgif',
        IMAGETYPE_JPEG=>'imagecreatefromjpeg',
        IMAGETYPE_PNG=>'imagecreatefrompng'
    );
    private $aCreateImagen = array(
        IMAGETYPE_GIF=>'imagegif',
        IMAGETYPE_JPEG=>'imagejpeg',
        IMAGETYPE_PNG=>'imagepng',
    );
	
    /**
    * @param string  Nombre del archivo
    */
    function __construct($source)
    {
        $this->source=$source;  
        if(is_file($source)){
            list($this->width_s,$this->height_s,$type,$attr) = getimagesize($source);
            $createFunc = $this->aCreateFunctions[$type];
            
            if($createFunc) {
                eval('$this->gd_s = '.$createFunc.'($source);');                
            }
            else{
                throw new Exception("Formato de Imagen no soportador.");
            }
        }
        else{
            throw new Exception("NO se envio recurso de imagen.");
        }
    }

    /**
    * Retorno de imagen creada por gd
    * @param    int     ancho en pixel
    */
    function get_source(){
        return $this->gd_s;
    }

    /**
    * Retorno de imagen creada por gd
    * @param    int     ancho en pixel
    */
    function activeGray(){
        imagefilter($this->gd_s, IMG_FILTER_GRAYSCALE);
    }

    /**
    * Retorno de imagen creada por gd
    * @param    int     ancho en pixel
    */
    function get_gd_source(){
        return $this->gd_d;
    }

    /**
    * Redimensiona la imagen de forma proporcional, a partir del ancho
    * @param    int     ancho en pixel
    */
    function resizeWidth($width_d) 
    {
        $height_d = floor(($width_d*$this->height_s) /$this->width_s);
        $this->resizeWidthHeight($width_d, $height_d);
    }
    /**
    * Redimensiona la imagen de forma proporcional, a partir del alto
    * @param    int     alto en pixel
    */
    function resizeHeight($height_d) 
    {
        $width_d = floor(($height_d*$this->width_s) /$this->height_s);
        $this->resizeWidthHeight($width_d, $height_d);
    }

    /**
    * Redimensiona la imagen de forma proporcional, a partir del porcentaje del �rea
    * @param    int     porcentaje de �rea
    */
    function resizeArea($perc) 
    {
        $factor = sqrt($perc/100);
        $this->resizeWidthHeight($this->width_s*$factor, $this->height_s*$factor);
    }

    /*Redimensiona por el lado mayor*/

    function resizeHighSide($width_d, $height_d){
        //hallando lado mayor
        if($this->width_s<=$this->height_s){
            $width_res = floor(($height_d*$this->width_s) /$this->height_s);
            if($width_res>$width_d){
                $this->resizeWidth($width_d);
            }
            else{
                $this->resizeHeight($height_d);
            }   
        }
        else{
            $height_res = floor(($width_d*$this->height_s) /$this->width_s);
            if($height_res>$height_d){
                $this->resizeHeight($height_d);
            }
            else{
                $this->resizeWidth($width_d);
            }			
        }
    }
    
    /**
    * Redimensiona la imagen, a partir de un ancho y alto determinado
    * @param    int     porcentaje de �rea
    */
    function resizeWidthHeight($width_d, $height_d) 
    {
        #bg
        $this->gd_d = imagecreatetruecolor($width_d, $height_d);
        imagealphablending($this->gd_d, false);
        imagesavealpha($this->gd_d,true);
        $transparent = imagecolorallocatealpha($this->gd_d, 255, 255, 255, 127);
        imagefilledrectangle($this->gd_d, 0, 0, $width_d, $height_d, $transparent);
                
        #sourcer
        imagealphablending($this->gd_s, true);
        imagesavealpha($this->gd_s, true);
        
        $status='';
        if($this->force_resize||($width_d<=$this->width_s&&$height_d<=$this->height_s)){
            
            imagecopyresampled($this->gd_d, $this->gd_s, 0, 0, 0, 0, $width_d, $height_d, $this->width_s, $this->height_s);
            
        }
        else{
            #sourcer
            imagealphablending($this->gd_s, true);
            imagesavealpha($this->gd_s, true);
            $this->gd_d=$this->gd_s;
        }
        
    }

    /**
    * Redimensiona la imagen, a partir de un ancho y alto determinado
    * @param    int     porcentaje de �rea
    */
    function cropImage($width_d, $height_d, $point_x='0', $point_y='0')
    {
        #bg
        $this->gd_d = imagecreatetruecolor($width_d, $height_d);
        imagealphablending($this->gd_d, false);
        imagesavealpha($this->gd_d,true);
        $transparent = imagecolorallocatealpha($this->gd_d, 255, 255, 255, 127);
        imagefilledrectangle($this->gd_d, 0, 0, $width_d, $height_d, $transparent);
                
        #sourcer
        imagealphablending($this->gd_s, true);
        imagesavealpha($this->gd_s, true);
        
        imagecopyresampled($this->gd_d, $this->gd_s, 0, 0, 0, 0, $width_d, $height_d, $width_d, $height_d);
            
    }

    
     /**
    * Redimensiona la imagen, a partir de un ancho y alto determinado
    * @param    int     porcentaje de �rea
    */
    function thumbnail($width_d, $height_d,$point_x='0', $point_y='0')
    {
        //hallando lado mayor
        $width_fin=$height_fin=0;
        
        $height_tmp = floor(($width_d*$this->height_s) /$this->width_s);
        $width_tmp = floor(($height_d*$this->width_s) /$this->height_s);
           
        if($this->height_s<$height_d&&$this->width_s<$width_d){
            $width_fin=$this->width_s;
            $height_fin=$this->height_s;
        }
        elseif($height_tmp>=$height_d){
            $width_fin=$width_tmp;
            $height_fin=$height_d;
        }
        else{
            $width_fin=$width_d;
            $height_fin=$height_tmp;		
        }

        #bg
        $this->gd_d = imagecreatetruecolor($width_d, $height_d);
        imagealphablending($this->gd_d, false);
        imagesavealpha($this->gd_d,true);
        $transparent = imagecolorallocatealpha($this->gd_d, 255, 255, 255, 127);
        imagefilledrectangle($this->gd_d, 0, 0, $width_d, $height_d, $transparent);
                
        #sourcer
        imagealphablending($this->gd_s, true);
        imagesavealpha($this->gd_s, true);
        
        if(empty($point_x)){
            $point_x=abs(($width_fin - $width_d) / 2);
        }
        if(empty($point_y)){
            $point_y=abs(($height_fin - $height_d) / 2);
        }
        
        imagecopyresampled($this->gd_d, $this->gd_s, $point_x, $point_y, 0, 0, $width_fin, $height_fin, $this->width_s, $this->height_s);
         
         // Write the string at the top left
        #$textcolor = imagecolorallocate($this->gd_d, 0, 0, 255);
        #imagestring($this->gd_d, 5, 0, 0, 'Or:'.$point_x.'|'.$point_y, $textcolor);
        #imagestring($this->gd_d, 5, 0, 20, 'Dt:'.$width_fin.'|'.$height_fin, $textcolor);
    }
    
    /**
    * Redimensiona la imagen, a partir de un ancho y alto determinado
    * @param    int     porcentaje de �rea
    */
    function thumbnailCrop($width_d, $height_d,$point_x='0', $point_y='0')
    {
        //hallando lado mayor
        $width_fin=$height_fin=0;
        
        $height_tmp = floor(($width_d*$this->height_s) /$this->width_s);
        $width_tmp = floor(($height_d*$this->width_s) /$this->height_s);
           
        if($height_tmp<$height_d){
            $width_fin=$width_tmp;
            $height_fin=$height_d;
        }
        elseif($height_tmp>=$height_d){
            $width_fin=$width_d;
            $height_fin=$height_tmp;
        }
        else{
            $width_fin=$width_d;
            $height_fin=$height_d;		
        }

        #bg
        $this->gd_d = imagecreatetruecolor($width_fin, $height_fin);
        imagealphablending($this->gd_d, false);
        imagesavealpha($this->gd_d,true);
        $transparent = imagecolorallocatealpha($this->gd_d, 255, 255, 255, 127);
        imagefilledrectangle($this->gd_d, 0, 0, $height_fin, $height_fin, $transparent);
                
        #sourcer
        imagealphablending($this->gd_s, true);
        imagesavealpha($this->gd_s, true);
        
        imagecopyresampled($this->gd_d, $this->gd_s, 0, 0, 0, 0, $width_fin, $height_fin, $this->width_s, $this->height_s);
                
	$this->gd_s=$this->gd_d;
        $this->width_s = imagesx($this->gd_s);
        $this->height_s =imagesy($this->gd_s);
        
        $point_x=intval(($this->width_s-$width_d)/2);
        $point_y=intval(($this->height_s-$height_d)/2);
		
        $this->cropImage($width_d,$height_d,$point_x, $point_y);
        
    }

	/**
    * Graba la imagen a un archivo de destino
    * @param  string $formato extension
    * @param  string $file_d  Nombre del archivo de salida
    * retorna true si se cre�
    */
    function show($formato=IMAGETYPE_JPEG)
    {
        $createImg = $this->aCreateImagen[$formato];

        if(empty($createImg)){
            $createImg=$this->aCreateImagen[IMAGETYPE_JPEG];
        }

        if(empty($this->gd_d)) $this->gd_d=$this->gd_s;

        header('Content-type: image/jpeg');
        eval($createImg.'($this->gd_d);');  
        imagedestroy($this->gd_d);
        imagedestroy($this->gd_s);
    }

    /**
    * Graba la imagen a un archivo de destino
    * @param  string $formato extension
    * @param  string $file_d  Nombre del archivo de salida
    * retorna true si se cre�
    */
    function save($file_d=null)
    {
        $file=(empty($file_d)?$this->source:$file_d);
        
        list($width_s,$height_s,$type,$attr) = getimagesize($this->source);

        ///tipo inagen salida
        if(empty($this->gd_d)){
            $this->gd_d=$this->gd_s;
        }
        
        ///tipo imagen salida
        switch($type){
            case IMAGETYPE_GIF  : imagegif($this->gd_d, $file); break;
            case IMAGETYPE_JPEG : imagejpeg($this->gd_d, $file, 100); break;
            case IMAGETYPE_PNG  : imagepng($this->gd_d, $file, 9); break;
            default             : imagejpeg($this->gd_d, $file, 100); break;
        }
        
        @imagedestroy($this->gd_d);
        @imagedestroy($this->gd_s);
    }
    
    
}
?>
