<?php

namespace App\Events;

use App\Events\Event;
use App\Repositories\Template\TemplateInterface;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class RenderImageAfterCreateTemplate extends Event
{
    /**
     * string html
     * @var $content
     */
    private $content;

    /**
     * Filename
     * @var $filename
     */
    private $filename;

    /**
     * Template Id
     * @var $template_id
     */
    private $template_id;
    private $PROPERTY;
    private $numPage;

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($template_id, $content, $slug)
    { 
        $this->template_id = $template_id;
        $this->content = $content;
        $this->filename = $slug;
        \Log::info('test thoi', [$this->filename]);
    }

    public function render(TemplateInterface $template)
    {
        // $this->content = replace_url_img($this->content);
        try {

            //  \PDF::loadView('api.template.index', ['content' => $this->content])
            // ->save(public_path('pdf/'.$this->filename.'.pdf'));
           //  \App::make('dompdf.wrapper')->loadView('api.template.index', ['content' => $this->content])
           // ->save(public_path('pdf/'.$this->filename.'.pdf'));
            $snappy = \App::make('snappy.pdf');
            $snappy->generateFromHtml(  $this->content, public_path('pdf/'.$this->filename.'.pdf'));
       
            $this->createImage();
            // convertPDFToIMG($this->filename);

            return $this->saveImage($template);
        } catch (\Exception $e) {
            \Log::info('null', [$e->getMessage()]);
            return null;
        }
    }

    /**
     * Create image
     * @return void
     */
    private function createImage()
    {
        $img = new \Imagick();
        $this->numPage = $img->getNumberImages();

        for ($i = 0; $i < $this->numPage; $i++) {
            $img->readImage(public_path('pdf/'.$this->filename.'.pdf['.$i.']'));
            $img->setImageFormat('jpg');
            $img->setSize(595, 842);
            $img->writeImage(public_path('images/template/'.$this->filename.'-'.$i.'.jpg'));
        }
        
        $img->clear();
        $img->destroy();
    }

    /**
     * Save thumbnail
     * @param  TemplateInterface $template 
     * @return mixed           
     */
    private function saveImage($templateInterface)
    {
        $images = [];
        for ($i = 0; $i < $this->numPage; $i++) {
            $resize = \Image::make(public_path('images/template/'.$this->filename.'-'.$i.'.jpg'))
                ->resize(200,150)
                ->save(public_path('thumb/template/'.$this->filename.'-'.$i.'.jpg'));
            $images['origin'][] = public_path('images/template/'.$this->filename.'-'.$i.'.jpg');
            $images['thumb'][] = public_path('thumb/template/'.$this->filename.'-'.$i.'.jpg');
        }
       
        
        if (!$resize) return null;

        $template = $templateInterface->getById($this->template_id);

        $template->image = $images;
        $template->source_file_pdf = 'pdf/'.$this->filename.'.pdf';
        
        return $template->save();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
