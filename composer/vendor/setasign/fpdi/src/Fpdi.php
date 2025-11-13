<?php

/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2020 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */

namespace setasign\Fpdi;

use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfIndirectObject;
use setasign\Fpdi\PdfParser\Type\PdfNull;

/**
 * Class Fpdi
 *
 * This class let you import pages of existing PDF documents into a reusable structure for FPDF.
 */
class Fpdi extends FpdfTpl
{
    use FpdiTrait;

    /**
     * FPDI version
     *
     * @string
     */
    const VERSION = '2.3.4';

    protected function _enddoc()
    {
        parent::_enddoc();
        $this->cleanUp();
    }

    /**
     * Draws an imported page or a template onto the page or another template.
     *
     * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
     * aspect ratio.
     *
     * @param mixed $tpl The template id
     * @param float|int|array $x The abscissa of upper-left corner. Alternatively you could use an assoc array
     *                           with the keys "x", "y", "width", "height", "adjustPageSize".
     * @param float|int $y The ordinate of upper-left corner.
     * @param float|int|null $width The width.
     * @param float|int|null $height The height.
     * @param bool $adjustPageSize
     * @return array The size
     * @see Fpdi::getTemplateSize()
     */
    public function useTemplate($tpl, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false)
    {
        if (isset($this->importedPages[$tpl])) {
            $size = $this->useImportedPage($tpl, $x, $y, $width, $height, $adjustPageSize);
            if ($this->currentTemplateId !== null) {
                $this->templates[$this->currentTemplateId]['resources']['templates']['importedPages'][$tpl] = $tpl;
            }
            return $size;
        }

        return parent::useTemplate($tpl, $x, $y, $width, $height, $adjustPageSize);
    }

    /**
     * Get the size of an imported page or template.
     *
     * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
     * aspect ratio.
     *
     * @param mixed $tpl The template id
     * @param float|int|null $width The width.
     * @param float|int|null $height The height.
     * @return array|bool An array with following keys: width, height, 0 (=width), 1 (=height), orientation (L or P)
     */
    public function getTemplateSize($tpl, $width = null, $height = null)
    {
        $size = parent::getTemplateSize($tpl, $width, $height);
        if ($size === false) {
            return $this->getImportedPageSize($tpl, $width, $height);
        }
        
        return $size;
    }

    var $widths;

    function SetWidths($w)
    {
        $this->widths = $w;
    }

    public function Row($yksklk, $data)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i ++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = $yksklk * $nb;
        $this->CheckPageBreak($h);
        for ($i = 0; $i < count($data); $i ++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);
            $this->MultiCell($w, $yksklk, iconv('utf-8', 'iso-8859-9', $data[$i]), 0, $a);
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
    }

    private static function chata($str)
    {
        $src = array(
            chr(145),
            chr(146),
            chr(147),
            chr(148),
            chr(151)
        );
        
        $rl = array(
            "'",
            "'",
            '"',
            '"',
            '-'
        );
        return str_replace($src, $rl, $str);
    }

    public function undrawRow($yksklk, $data)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i ++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = $yksklk * $nb;
        $this->CheckPageBreak($h);
        for ($i = 0; $i < count($data); $i ++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            // $this->Rect($x,$y,$w,$h);
            $this->MultiCell($w, $yksklk, iconv('utf-8', 'iso-8859-9//TRANSLIT', Fpdi::chata($data[$i])), 0, $a);
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        // If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        // Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb --;
        $sep = - 1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i ++;
                $sep = - 1;
                $j = $i;
                $l = 0;
                $nl ++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == - 1) {
                    if ($i == $j)
                        $i ++;
                } else
                    $i = $sep + 1;
                $sep = - 1;
                $j = $i;
                $l = 0;
                $nl ++;
                        }
                        else
                            $i++;
                }
                return $nl;
    }
    
    /**
     * @inheritdoc
     * @throws CrossReferenceException
     * @throws PdfParserException
     */
    protected function _putimages()
    {
        $this->currentReaderId = null;
        parent::_putimages();

        foreach ($this->importedPages as $key => $pageData) {
            $this->_newobj();
            $this->importedPages[$key]['objectNumber'] = $this->n;
            $this->currentReaderId = $pageData['readerId'];
            $this->writePdfType($pageData['stream']);
            $this->_put('endobj');
        }

        foreach (\array_keys($this->readers) as $readerId) {
            $parser = $this->getPdfReader($readerId)->getParser();
            $this->currentReaderId = $readerId;

            while (($objectNumber = \array_pop($this->objectsToCopy[$readerId])) !== null) {
                try {
                    $object = $parser->getIndirectObject($objectNumber);
                } catch (CrossReferenceException $e) {
                    if ($e->getCode() === CrossReferenceException::OBJECT_NOT_FOUND) {
                        $object = PdfIndirectObject::create($objectNumber, 0, new PdfNull());
                    } else {
                        throw $e;
                    }
                }

                $this->writePdfType($object);
            }
        }

        $this->currentReaderId = null;
    }

    /**
     * @inheritdoc
     */
    protected function _putxobjectdict()
    {
        foreach ($this->importedPages as $key => $pageData) {
            $this->_put('/' . $pageData['id'] . ' ' . $pageData['objectNumber'] . ' 0 R');
        }

        parent::_putxobjectdict();
    }

    /**
     * @inheritdoc
     */
    protected function _put($s, $newLine = true)
    {
        if ($newLine) {
            $this->buffer .= $s . "\n";
        } else {
            $this->buffer .= $s;
        }
    }
}
