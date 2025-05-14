<?php
namespace Fourviewture\Newssync\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Kay Strobach <typo3@kay-strobach.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Pages
 */
class Page extends AbstractEntity
{

    /**
     * uid
     *
     * @var int
     */
    protected ?int $uid = null;

    /**
     * pid
     *
     * @var int
     */
    protected ?int $pid = null;

    /**
     * title
     *
     * @var string
     */
    protected ?string $navTitle = null;

    /**
     * Doktype
     *
     * @var string
     */
    protected $doktype = null;

    /**
     * @return string
     */
    public function getNavTitle()
    {
        return $this->navTitle;
    }

    /**
     * @param string $navTitle
     */
    public function setNavTitle($navTitle)
    {
        $this->navTitle = $navTitle;
    }

    /**
     * Returns the uid
     *
     * @return int
     */
    public function getUid(): ?int
    {
        return $this->uid;
    }

    /**
     * Returns the pid
     *
     * @return int
     */
    public function getPid(): ?int
    {
        return $this->pid;
    }

    /**
     * @return string
     */
    public function getDoktype()
    {
        return $this->doktype;
    }

    /**
     * @param string $doktype
     */
    public function setDoktype($doktype)
    {
        $this->doktype = $doktype;
    }
}
