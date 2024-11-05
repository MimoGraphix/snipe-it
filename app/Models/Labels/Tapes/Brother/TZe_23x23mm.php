<?php

namespace App\Models\Labels\Tapes\Brother;

use App\Helpers\Helper;
use App\Models\Labels\Label;

class TZe_23x23mm extends Label
{
    private const BARCODE_MARGIN =   1.40;
    private const TAG_SIZE       =   3.00;
    private const TITLE_SIZE     =   2.80;
    private const TITLE_MARGIN   =   0.50;
    private const LABEL_SIZE     =   2.00;
    private const LABEL_MARGIN   = - 0.35;
    private const FIELD_SIZE     =   3.20;
    private const FIELD_MARGIN   =   0.15;

    private const HEIGHT       = 23.00;
    private const MARGIN_SIDES =  3.20;
    private const MARGIN_ENDS  =  3.20;

    public function getHeight()       { return Helper::convertUnit(self::HEIGHT, 'mm', $this->getUnit()); }
    public function getWidth()        { return Helper::convertUnit(self::HEIGHT, 'mm', $this->getUnit()); }

    public function getMarginTop()    { return Helper::convertUnit(self::MARGIN_SIDES, 'mm', $this->getUnit()); }
    public function getMarginBottom() { return Helper::convertUnit(self::MARGIN_SIDES, 'mm', $this->getUnit());}
    public function getMarginLeft()   { return Helper::convertUnit(self::MARGIN_ENDS, 'mm', $this->getUnit()); }
    public function getMarginRight()  { return Helper::convertUnit(self::MARGIN_ENDS, 'mm', $this->getUnit()); }

    public function getUnit()  { return 'mm'; }
    public function getSupportAssetTag()  { return true; }
    public function getSupport1DBarcode() { return false; }
    public function getSupport2DBarcode() { return true; }
    public function getSupportFields()    { return 0; }
    public function getSupportLogo()      { return false; }
    public function getSupportTitle()     { return false; }

    public function preparePDF($pdf) {}

    public function write($pdf, $record) {
        $pa = $this->getPrintableArea();

        $currentX = $pa->x1 + (self::TAG_SIZE / 2);
        $currentY = $pa->y1;
        $usableWidth = $pa->w;

        $barcodeSize = $pa->h - self::TAG_SIZE;

        if ($record->has('barcode2d')) {
            static::write2DBarcode(
                $pdf, $record->get('barcode2d')->content, $record->get('barcode2d')->type,
                $currentX, $currentY,
                $barcodeSize, $barcodeSize
            );
            $barcodeSize += self::TAG_SIZE;
            static::writeText(
                $pdf, "#" . $record->get('tag'),
                $pa->x1, $pa->y2 - self::TAG_SIZE,
                'freemono', 'b', self::TAG_SIZE, 'C',
                $barcodeSize, self::TAG_SIZE, true, 0
            );
        } else {
            static::writeText(
                $pdf,"#" . $record->get('tag'),
                $pa->x1, $pa->y2 - self::TAG_SIZE,
                'freemono', 'b', self::TAG_SIZE, 'R',
                $usableWidth, self::TAG_SIZE, true, 0
            );
        }
    }
}