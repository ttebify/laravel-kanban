<?php

namespace JinoAntony\Kanban;

abstract class Kanban
{
    /**
     * Kanban Boards
     *
     * @var Kboard[]
     */
    protected $boards;

    /**
     * Selector of kanban conatiner
     *
     * @var string
     */
    protected $element;

    /**
     * Margin between boards
     *
     * @var string
     */
    protected $gutter = '20px';

    /**
     * Width of the boards
     *
     * @var string
     */
    protected $width = '250px';

    /**
     * The name of the js object
     *
     * @var string
     */
    protected $jsObjName = 'kanban';

    /**
     * Can drag items in the board
     *
     * @var boolean
     */
    protected $dragItems = true;

    /**
     * Can drag boards
     *
     * @var boolean
     */
    protected $dragBoards = true;

    /**
     * Specify whether to use responsive layout for boards
     *
     * @var boolean
     */
    protected $isResponsive = false;

    /**
     * Create new Kanban
     *
     * @param array $boards
     */
    public function __construct(array $boards = [])
    {
        $this->boards = $boards;
    }

    /**
     * Set the kanban container element
     *
     * @param string $element
     * @return $this
     */
    public function element(string $element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Set the margin between boards
     *
     * @param string $margin
     * @return $this
     */
    public function margin(string $margin)
    {
        $this->gutter = $margin;

        return $this;
    }

    /**
     * Set the width of the container
     *
     * @param string $width
     * @return $this
     */
    public function width(string $width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set the name for js object variable
     *
     * @param string $objName
     * @return $this
     */
    public function objectName(string $objName)
    {
        $this->jsObjName = $objName;

        return $this;
    }

    /**
     * Specify the items are draggable or not
     *
     * @param boolean $dragItems
     * @return $this
     */
    public function dragItems(bool $dragItems)
    {
        $this->dragItems = $dragItems;

        return $this;
    }

    /**
     * Specify the boards are draggable or not
     *
     * @param boolean $dragBoards
     * @return $this
     */
    public function dragBoards(bool $dragBoards)
    {
        $this->dragBoards = $dragBoards;

        return $this;
    }

    /**
     * Specify whether to use responsive layout or not
     *
     * @param boolean $isResponsive
     * @return $this
     */
    public function responsive(bool $isResponsive)
    {
        $this->isResponsive = $isResponsive;

        return $this;
    }

    /**
     * Attach the kanban boards
     *
     * @param KBoard[] $boards
     * @return $this
     */
    public function boards(array $boards)
    {
        $this->boards = $boards;

        return $this;
    }

    /**
     * Add a new board to the boards list
     *
     * @param KBoard $board
     * @return $this
     */
    public function addBoard(KBoard $board)
    {
        $this->boards[] = $board;

        return $this;
    }

    /**
     * Render the kanban board
     *
     * @param string $view
     * @param array $viewData
     * @return \Illuminate\Contracts\View\View
     */
    public function render(string $view, array $viewData = [])
    {
        $this->boards($this->getBoards());
        $data = $this->data();

        foreach ($this->boards as $board) {
            $id = $board->getId();
            $board->setItems($data[$id]);
        }

        $this->build();

        return view($view, array_merge($viewData, ['kanban' => $this]));
    }

    /**
     * Render the scripts for kanban
     *
     * @return string
     */
    public function scripts()
    {
        return view('laravel-kanban::script', [
            'element' => $this->element,
            'margin' => $this->gutter,
            'width' => $this->width,
            'boards' => $this->formatBoards(),
            'jsObjName' => $this->jsObjName,
            'dragItems' => $this->dragItems,
            'dragBoards' => $this->dragBoards,
            'isResponsive' => $this->isResponsive,
        ])->render();
    }

    /**
     * Format the kanban boards
     *
     * @return array
     */
    protected function formatBoards()
    {
        $formattedBoards = [];

        foreach ($this->boards as $board) {
            $formattedBoards[] = $board->getFormattedBoard();
        }

        return $formattedBoards;
    }

    /**
     * Get the data for each board
     *
     * @return array
     */
    abstract public function data();

    /**
     * Get the list of boards
     *
     * @return KBoard[]
     */
    abstract public function getBoards();

    /**
     * Build the kanban board
     *
     * @return void
     */
    public function build()
    {
        return $this->element('.kanban');
    }
}