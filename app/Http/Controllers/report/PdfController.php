<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use PDF;

class PdfController extends Controller
{
    public function pdf(){
        // return ['sdf'];
        // $mpdf =  new PDF([
        //     'default_font_size' => 12,
        //     'default_font' => 'nikosh'
        // ]);

        // $mpdf->WriteHTML($this->pdfHTML());
        // $mpdf->Output();
        $data = ['s'];
        $pdf=PDF::loadView('pdf',$data,['data'=>'hello'],[],
            [
                'mode'                 => '',
                'format'               => 'A4',
                'default_font_size'    => '12',
                'default_font'         => 'sans-serif',
                'margin_left'          => 5,
                'margin_right'         => 5,
                'margin_top'           => 25,
                'margin_bottom'        => 5,
                'margin_header'        => 0,
                'margin_footer'        => 0,
                'orientation'          => 'p',
                'title'                => 'Laravel mPDF',
                'author'               => '',
                'watermark'            => '',
                'show_watermark'       => true,
                'watermark_font'       => 'sans-serif',
                'display_mode'         => 'fullpage',
                'watermark_text_alpha' => 0.1,
                'custom_font_dir'      => '',
                'custom_font_data' 	   => [],
                'auto_language_detection'  => false,
                'temp_dir'               => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
                'pdfa' 			=> false,
                'pdfaauto' 		=> false,
            ]
        );
        return $pdf->stream('report.pdf');
    }

}
