<?php

namespace App\Http\Services;

use setasign\Fpdi\Fpdi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Http\Services\Service;
use App\Notifications\Book\Completed as BookCompletedNotification;

class PdfService extends Service
{
    public function generate($input)
    {
        $attaches = [];

        $book = service('Book')->get($input['book_id']);
        $attaches[] = $this->certificate($book, $input);

        if ($book->course->id != 3) {
            $attaches[] = $this->congratulations($book, $input);
            $attaches[] = $this->report($book, $input);
        }

        $auth_token = md5(time().$book->id.$book->user->email);
        service('User')->update($book->user, [
            'auth_token' => $auth_token,
        ]);

        $book->user->notify((new BookCompletedNotification($book, $auth_token, $attaches)));

        service('Book')->update($book, [
            'status' => 7,
        ]);

        return true;
    }

    public function certificate($book, $input)
    {
        $name = strtoupper($input['firstname'].' '.$input['lastname']);
        $carbon = new Carbon($input['date']);
        $date = strtoupper($carbon->englishMonth.' '.$carbon->year);

        $filename = '';
        $new_filename = '';
        if ($book->course->id == 1) {
            $filename = 'Bikemaster_Level_'.$book->level->level.'_certification_master.pdf';
            $new_filename = $book->user->id.'/Bikemaster_Level_'.$book->level->level.'_certification_master-'.$input['date'].'.pdf';
        }

        if ($book->course->id == 2) {
            $filename = 'Roadmaster_Level_'.$book->level->level.'_certification_master.pdf';
            $new_filename = $book->user->id.'/Roadmaster_Level_'.$book->level->level.'_certification_master-'.$input['date'].'.pdf';
        }

        if ($book->course->id == 3) {
            $filename = 'Bespoke_certification_master.pdf';
            $new_filename = $book->user->id.'/Bespoke_certification_master-'.$input['date'].'.pdf';
        }

        $pdf = new Fpdi;

        $pdf->setSourceFile(storage_path('app/certificates/'.$filename));

        // We import only page 1
        $tpl = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($tpl);
        
        $pdf->AddPage();
        // Let's use it as a template from top-left corner to full width and height
        $pdf->useTemplate($tpl, 0, 0, null, null, true);

        // Set font and color
        $pdf->SetFont('Helvetica', 'I', 20); // Font Name, Font Style (eg. 'B' for Bold), Font Size
        $pdf->SetTextColor(93, 101, 120); // RGB

        // Position our "cursor" to left edge and in the middle in vertical position minus 1/2 of the font size
        $pdf->SetXY(60, 91);

        // Add text cell that has full page width and height of our font
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell($size['width'] - 23 - 60, 10, $name.'   ', 0, 2, 'R', true);

        // Set font and color
        $pdf->SetFont('Helvetica', '', 8); // Font Name, Font Style (eg. 'B' for Bold), Font Size
        $pdf->SetTextColor(93, 101, 120); // RGB

        // Position our "cursor" to left edge and in the middle in vertical position minus 1/2 of the font size
        $pdf->SetXY(246, 160);

        // Add text cell that has full page width and height of our font
        $pdf->setFillColor(255, 255, 255); 
        $pdf->Cell(30, 8, $date, 0, 2, 'C', true);

        // Output our new pdf into a file
        // F = Write local file
        // I = Send to standard output (browser)
        // D = Download file
        // S = Return PDF as a string
        if ( ! file_exists(storage_path('app/public/certificates/'.$book->user->id.'/'))) {
            Storage::makeDirectory('public/certificates/'.$book->user->id.'/');
        }
        $pdf->Output(storage_path('app/public/certificates/'.$new_filename), 'F');

        service('Doc')->save($book->user, $book, [
            'type' => 'certificate',
            'file' => 'certificates/'.$new_filename,
            'is_signed' => true,
            'date' => $carbon,
        ]);

        return storage_path('app/public/certificates/'.$new_filename);
    }

    public function congratulations($book, $input)
    {
        $name = $input['firstname'];
        $carbon = new Carbon($input['date']);

        $filename = '';
        $new_filename = '';
        if ($book->course->id == 1) {
            $filename = 'Bikemaster_Level_'.$book->level->level.'_congratulations_letter.pdf';
            $new_filename = $book->user->id.'/Bikemaster_Level_'.$book->level->level.'_congratulations_letter-'.$input['date'].'.pdf';
        }

        if ($book->course->id == 2) {
            $filename = 'Roadmaster_Level_'.$book->level->level.'_congratulations_letter.pdf';
            $new_filename = $book->user->id.'/Roadmaster_Level_'.$book->level->level.'_congratulations_letter-'.$input['date'].'.pdf';
        }

        if ($book->course->id == 3) {
            $filename = 'Bespoke_congratulations_letter.pdf';
            $new_filename = $book->user->id.'/Bespoke_congratulations_letter-'.$input['date'].'.pdf';
        }

        $pdf = new Fpdi;

        $pdf->setSourceFile(storage_path('app/certificates/'.$filename));

        // We import only page 1
        $tpl = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($tpl);
        
        $pdf->AddPage();
        // Let's use it as a template from top-left corner to full width and height
        $pdf->useTemplate($tpl, 0, 0, null, null, true);

        // Set font and color
        $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
        $pdf->SetTextColor(0, 0, 0); // RGB

        // Position our "cursor" to left edge and in the middle in vertical position minus 1/2 of the font size
        $pdf->SetXY(29, 89.2);

        // Add text cell that has full page width and height of our font
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell($size['width'] - 30, 10, $name.'   ', 0, 2, 'L', true);

        // Output our new pdf into a file
        // F = Write local file
        // I = Send to standard output (browser)
        // D = Download file
        // S = Return PDF as a string
        if ( ! file_exists(storage_path('app/public/certificates/'.$book->user->id.'/'))) {
            Storage::makeDirectory('public/certificates/'.$book->user->id.'/');
        }
        $pdf->Output(storage_path('app/public/certificates/'.$new_filename), 'F');

        service('Doc')->save($book->user, $book, [
            'type' => 'congratulation',
            'file' => 'certificates/'.$new_filename,
            'is_signed' => true,
            'date' => $carbon,
        ]);

        return storage_path('app/public/certificates/'.$new_filename);
    }

    public function report($book, $input)
    {
        $carbon = new Carbon($input['date']);
        $filename = 'Rapid_Rider_Report_master_v1.pdf';
        $new_filename = $book->user->id.'/Rapid_Rider_Report_master_v1-'.$input['date'].'.pdf';
        
        $pdf = new Fpdi;

        $pdf->setSourceFile(storage_path('app/certificates/'.$filename));

        $tpl = $pdf->importPage(1);
        $pdf->AddPage();
        $pdf->useTemplate($tpl, 0, 0, null, null, true);

        $tpl = $pdf->importPage(2);
        $pdf->AddPage();
        $pdf->useTemplate($tpl, 0, 0, null, null, true);

        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetXY(45, 35.5);
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell(54, 5, $input['id'], 0, 2, 'L', true);

        $pdf->SetXY(45, 40.3);
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell(54, 5, $input['firstname'], 0, 2, 'L', true);

        $pdf->SetXY(45, 45.4);
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell(54, 5, $input['lastname'], 0, 2, 'L', true);

        $pdf->SetXY(55, 50.5);
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell(44, 4, $input['prevCourse'], 0, 2, 'L', true);

        /* --- */

        $pdf->SetXY(145, 35.5);
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell(50, 5, $input['coach'], 0, 2, 'L', true);

        $pdf->SetXY(145, 40.3);
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell(50, 5, $input['startDate'], 0, 2, 'L', true);

        $pdf->SetXY(145, 45.4);
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell(50, 5, $input['endDate'], 0, 2, 'L', true);

        $pdf->SetXY(145, 50.5);
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell(50, 4, $input['moto'], 0, 2, 'L', true);

        /* --- */

        $x = 0;
        $y = 0;
        if ($book->course->id == 1) {
            if ($book->level->level == 1) {
                $x = 93.5;
                $y = 89.5;
            }

            if ($book->level->level == 2) {
                $x = 93.5;
                $y = 93.2;
            }

            if ($book->level->level == 3) {
                $x = 93.5;
                $y = 97;
            }
        }

        if ($book->course->id == 2) {
            if ($book->level->level == 1) {
                $x = 154.8;
                $y = 89.5;
            }

            if ($book->level->level == 2) {
                $x = 154.8;
                $y = 93.2;
            }

            if ($book->level->level == 3) {
                $x = 154.8;
                $y = 97;
            }
        }
        
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetXY($x, $y);
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell(6.5, 3, 'X', 0, 2, 'C', true);

        /* --- */

        if ( ! empty($input['road']['forward'])) {
            $pdf->SetXY(93.8, 137);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['road']['interpretion'])) {
            $pdf->SetXY(93.8, 142);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['road']['perception'])) {
            $pdf->SetXY(93.8, 147);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        /* --- */

        if ( ! empty($input['planning']['position'])) {
            $pdf->SetXY(93.8, 156);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['planning']['speed'])) {
            $pdf->SetXY(93.8, 160.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['planning']['gear'])) {
            $pdf->SetXY(93.8, 165);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['planning']['signal'])) {
            $pdf->SetXY(93.8, 170);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        /* --- */

        if ( ! empty($input['control']['position'])) {
            $pdf->SetXY(93.8, 179);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['control']['technique'])) {
            $pdf->SetXY(93.8, 183.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['control']['gear'])) {
            $pdf->SetXY(93.8, 188);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['control']['control'])) {
            $pdf->SetXY(93.8, 192.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['control']['braking'])) {
            $pdf->SetXY(93.8, 197);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['control']['moving'])) {
            $pdf->SetXY(93.8, 202);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['control']['acceleration'])) {
            $pdf->SetXY(93.8, 206.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        /* --- */

        if ( ! empty($input['general']['development'])) {
            $pdf->SetXY(94, 223.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['general']['safety'])) {
            $pdf->SetXY(94, 228);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['general']['courtesy'])) {
            $pdf->SetXY(94, 232.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['general']['progress'])) {
            $pdf->SetXY(94, 237.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['general']['flow'])) {
            $pdf->SetXY(94, 242);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        /* --- */

        if ( ! empty($input['cornering']['assessment'])) {
            $pdf->SetXY(187.8, 132.7);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['cornering']['awareness'])) {
            $pdf->SetXY(187.8, 137.3);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['cornering']['position'])) {
            $pdf->SetXY(187.8, 142);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['cornering']['speed'])) {
            $pdf->SetXY(187.8, 146.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['cornering']['gear'])) {
            $pdf->SetXY(187.8, 151.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['cornering']['visual'])) {
            $pdf->SetXY(187.8, 156);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['cornering']['steering'])) {
            $pdf->SetXY(187.8, 160.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['cornering']['throttle'])) {
            $pdf->SetXY(187.8, 165);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['cornering']['progress'])) {
            $pdf->SetXY(187.8, 170);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        /* --- */

        if ( ! empty($input['urban']['hazards'])) {
            $pdf->SetXY(187.8, 188.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['urban']['mirrors'])) {
            $pdf->SetXY(187.8, 193);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['urban']['signals'])) {
            $pdf->SetXY(187.8, 197.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['urban']['approriate'])) {
            $pdf->SetXY(187.8, 202);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['urban']['acceleration'])) {
            $pdf->SetXY(187.8, 207);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        /* --- */

        if ( ! empty($input['overtalking']['opportunity'])) {
            $pdf->SetXY(187.8, 223.5);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['overtalking']['timing'])) {
            $pdf->SetXY(187.8, 228.2);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['overtalking']['appropriate'])) {
            $pdf->SetXY(187.8, 233);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        if ( ! empty($input['overtalking']['gap'])) {
            $pdf->SetXY(187.8, 238);
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(6.5, 3.5, 'X', 0, 2, 'C', true);
        }

        /* --- */

        $tpl = $pdf->importPage(3);
        $pdf->AddPage();
        $pdf->useTemplate($tpl, 0, 0, null, null, true);

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetXY(19, 38);
        $pdf->SetMargins(19, 38, 19);
        $pdf->Write(5,$input['summary']);

        /* --- */

        $tpl = $pdf->importPage(4);
        $pdf->AddPage();
        $pdf->useTemplate($tpl, 0, 0, null, null, true);

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetXY(19, 38);
        $pdf->SetMargins(19, 38, 19);
        $pdf->Write(5,$input['areas']);

        $pdf->SetXY(19, 142);
        $pdf->SetMargins(19, 38, 19);
        $pdf->Write(5,$input['areas']);

        $pdf->SetFont('Helvetica', '', 15);
        $pdf->SetXY(120, 215);
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell(35, 8, $input['date'], 0, 2, 'C', true);


        $pdf->fontpath = './fonts/';
        $pdf->AddFont('MrDafoe', '', 'MrDafoe-Regular.php');
        $pdf->SetFont('MrDafoe', '', 20);
        $pdf->SetXY(42, 214);
        $pdf->setFillColor(255, 255, 255);
        $pdf->Cell(58, 10, $input['coach'], 0, 2, 'L', true);

        if ( ! file_exists(storage_path('app/public/certificates/'.$book->user->id.'/'))) {
            Storage::makeDirectory('public/certificates/'.$book->user->id.'/');
        }
        $pdf->Output(storage_path('app/public/certificates/'.$new_filename), 'F');

        service('Doc')->save($book->user, $book, [
            'type' => 'report',
            'file' => 'certificates/'.$new_filename,
            'is_signed' => true,
            'date' => $carbon,
        ]);

        return storage_path('app/public/certificates/'.$new_filename);
    }
}
