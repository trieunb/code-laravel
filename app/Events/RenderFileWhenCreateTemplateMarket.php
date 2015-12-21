<?php

namespace App\Events;


use Anam\PhantomMagick\Converter;
use App\Events\Event;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class RenderFileWhenCreateTemplateMarket extends Event
{
    use SerializesModels;

    private $slug;
    private $content;
    private $template_id;
    private $pageNumb;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($slug, $content, $template_id)
    {
        $this->slug = $slug;
        $this->content = $content;
        $this->template_id = $template_id;
    }

    public function render(TemplateMarketInterface $template)
    {
        /*\PDF::loadView('admin.template.render', ['content' => $this->content])
            ->save(public_path('pdf/'.$this->slug.'.pdf'));*/
       /* $pdf_settings = \Config::get('laravel-tcpdf');
        $pdf = new \Elibyy\TCPDF\TCPdf($pdf_settings['page_orientation'], $pdf_settings['page_units'], $pdf_settings['page_format'], true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage();
        $pdf->writeHTML(view('admin.template.render', ['content' => $this->content])->render());
        $pdf->output(public_path('pdf/'.$this->slug.'.pdf'), 'F');*/
        $snappy = \App::make('snappy.pdf');
        if (\File::exists(public_path('pdf/'.$this->slug.'.pdf'))) {
            \File::delete(public_path('pdf/'.$this->slug.'.pdf'));
        }
        $snappy->generateFromHtml( $this->content, public_path('pdf/'.$this->slug.'.pdf'));

        $filename = $this->convertPDFToIMG($this->slug);
        $this->saveFile($template, $filename);
    }

    public function convertPDFToIMG($filename, $width = 500, $height = 700) {
        $imageFile = str_random(20).uniqid();
        $img = new \Imagick(public_path('pdf/'.$filename.'.pdf'));
        $this->pageNumb = $img->getNumberImages();
        for ($i = 0; $i < $this->pageNumb; $i++) {
            $img->readImage(public_path('pdf/'.$filename.'.pdf['.$i.']'));
            $img->setImageFormat('jpg');
            $img->setSize($width, $height);
            $img->writeImage(public_path('images/template/'.$imageFile.'-'.$i.'.jpg'));
        }
       
        $img->clear();
        $img->destroy();

        return $imageFile;
    }

    /**
     * Save thumbnail
     * @param  TemplateInterface $template 
     * @return mixed           
     */
    private function saveFile($TemplateMarketInterface, $filename)
    {
        $images = [];

        for ($i = 0; $i < $this->pageNumb; $i++) {
            $resize = \Image::make(public_path('images/template/'.$filename.'-'.$i.'.jpg'))
                ->resize(200,150)
                ->save(public_path('thumb/template/'.$filename.'-'.$i.'.jpg'));

            $images['origin'][] = 'images/template/'.$filename.'-'.$i.'.jpg';
            $images['thumb'][] = 'thumb/template/'.$filename.'-'.$i.'.jpg';
        }
        

        if (!$resize) return null;

        $template = $TemplateMarketInterface->getById($this->template_id);

        if (count($template->image['origin']) > 1) {
            \File::delete(implode(',', $template->image['origin']));
            \File::delete(implode(',', $template->image['thumb']));
        } elseif (count($template->image['origin']) == 1) {
            \File::delete($template->image['origin'][0]);
            \File::delete($template->image['thumb'][0]);
        }
        
        $template->image = $images;
        $template->source_file_pdf = 'pdf/'.$this->slug.'.pdf';
        
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
