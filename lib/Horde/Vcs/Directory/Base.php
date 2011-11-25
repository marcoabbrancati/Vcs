<?php
/**
 * Base directory class that stores information about the files in a single
 * directory in the repository.
 *
 * Copyright 2008-2011 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @package Vcs
 */
abstract class Horde_Vcs_Directory_Base
{
    /**
     * @var Horde_Vcs_Base
     */
    protected $_rep;

    /**
     * @var string
     */
    protected $_dirName;

    /**
     * @var array
     */
    protected $_files = array();

    /**
     * @var string
     */
    protected $_dirs = array();

    /**
     * @var string
     */
    protected $_moduleName;

    /**
     * Constructor.
     *
     * @param Horde_Vcs_Base $rep  A repository object.
     * @param string $dn           Path to the directory.
     * @param array $opts          TODO
     *
     * @throws Horde_Vcs_Exception
     */
    public function __construct(Horde_Vcs_Base $rep, $dn, $opts = array())
    {
        $this->_rep = $rep;
        $this->_moduleName = $dn;
        $this->_dirName = '/' . ltrim($dn, '/');
    }

    /**
     * TODO
     */
    public function queryDirList()
    {
        return $this->_dirs;
    }

    /**
     * TODO
     */
    public function queryFileList($showdeleted = false)
    {
        return $this->_files;
    }

    /**
     * Sorts the the directory contents.
     *
     * @param integer $how  Of the form Horde_Vcs::SORT_[*] where * can be:
     *                      NONE, NAME, AGE, REV for sorting by name, age or
     *                      revision.
     * @param integer $dir  Of the form Horde_Vcs::SORT_[*] where * can be:
     *                      ASCENDING, DESCENDING for the order of the sort.
     */
    public function applySort($how = Horde_Vcs::SORT_NONE,
                              $dir = Horde_Vcs::SORT_ASCENDING)
    {
        // Always sort directories by name.
        natcasesort($this->_dirs);

        $this->_doFileSort($this->_files, $how);

        if ($dir == Horde_Vcs::SORT_DESCENDING) {
            $this->_dirs = array_reverse($this->_dirs);
            $this->_files = array_reverse($this->_files);
        }
    }

    /**
     * TODO
     */
    protected function _doFileSort(&$fileList, $how = Horde_Vcs::SORT_NONE)
    {
        switch ($how) {
        case Horde_Vcs::SORT_AGE:
            usort($fileList, array($this, '_fileAgeSort'));
            break;

        case Horde_Vcs::SORT_NAME:
            usort($fileList, array($this, '_fileNameSort'));
            break;

        case Horde_Vcs::SORT_AUTHOR:
            usort($fileList, array($this, '_fileAuthorSort'));
            break;

        case Horde_Vcs::SORT_REV:
            usort($fileList, array($this, '_fileRevSort'));
            break;

        case Horde_Vcs::SORT_NONE:
        default:
            break;
        }
    }

    /**
     * Sort function for ascending age.
     */
    public function _fileAgeSort($a, $b)
    {
        $aa = $a->queryLastLog();
        $bb = $b->queryLastLog();
        return ($aa->queryDate() == $bb->queryDate())
            ? 0
            : (($aa->queryDate() < $bb->queryDate()) ? 1 : -1);
    }

    /**
     * Sort function by author name.
     */
    public function _fileAuthorSort($a, $b)
    {
        $aa = $a->queryLastLog();
        $bb = $b->queryLastLog();
        return ($aa->queryAuthor() == $bb->queryAuthor())
            ? 0
            : (($aa->queryAuthor() > $bb->queryAuthor()) ? 1 : -1);
    }

    /**
     * Sort function for ascending filename.
     */
    public function _fileNameSort($a, $b)
    {
        return strcasecmp($a->queryName(), $b->queryName());
    }

    /**
     * Sort function for ascending revision.
     */
    public function _fileRevSort($a, $b)
    {
        return $this->_rep->cmp($a->queryRevision(), $b->queryRevision());
    }

    /**
     * TODO
     */
    public function getBranches()
    {
        return array();
    }
}