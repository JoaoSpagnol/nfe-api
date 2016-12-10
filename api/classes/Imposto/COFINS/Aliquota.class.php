<?php
/**
 * MIT License
 * 
 * Copyright (c) 2016 MZ Desenvolvimento de Sistemas LTDA
 * 
 * @author Francimar Alves <mazinsw@gmail.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */
namespace Imposto\COFINS;
use Imposto;
use DOMDocument;

class Aliquota extends Imposto {

	const TRIBUTACAO_NORMAL = 'normal';
	const TRIBUTACAO_DIFERENCIADA = 'diferenciada';

	public function __construct($cofins = array()) {
		parent::__construct($cofins);
		$this->setGrupo(self::GRUPO_COFINS);
	}

	public function getTributacao($normalize = false) {
		if(!$normalize)
			return parent::getTributacao();
		switch (parent::getTributacao()) {
			case self::TRIBUTACAO_NORMAL:
				return '01';
			case self::TRIBUTACAO_DIFERENCIADA:
				return '02';
		}
		return parent::getTributacao($normalize);
	}

	public function toArray() {
		$cofins = parent::toArray();
		return $cofins;
	}

	public function fromArray($cofins = array()) {
		if($cofins instanceof Aliquota)
			$cofins = $cofins->toArray();
		else if(!is_array($cofins))
			return $this;
		parent::fromArray($cofins);
		if(is_null($this->getTributacao()))
			$this->setTributacao(self::TRIBUTACAO_NORMAL);
		return $this;
	}

	public function getNode($name = null) {
		$dom = new DOMDocument('1.0', 'UTF-8');
		$element = $dom->createElement(is_null($name)?'COFINSAliq':$name);
		$element->appendChild($dom->createElement('CST', $this->getTributacao(true)));
		$element->appendChild($dom->createElement('vBC', $this->getBase(true)));
		$element->appendChild($dom->createElement('pCOFINS', $this->getAliquota(true)));
		$element->appendChild($dom->createElement('vCOFINS', $this->getValor(true)));
		return $element;
	}

}