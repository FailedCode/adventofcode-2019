<?php


namespace App\Service;

class IntcodeComputer
{
    const OPCODE_ADD = 1;
    const OPCODE_MULTIPLY = 2;
    const OPCODE_INPUT = 3;
    const OPCODE_OUTPUT = 4;
    const OPCODE_HALT = 99;

    /**
     * Number of Ints to skip after handling opcode
     * Minmal 1, the opcode itself
     *
     * @var array
     */
    protected $opcodeInstructions = [
        self::OPCODE_ADD => 4,
        self::OPCODE_MULTIPLY => 4,
        self::OPCODE_INPUT => 2,
        self::OPCODE_OUTPUT => 2,
        self::OPCODE_HALT => 1,
    ];

    /**
     * Parameters are positions in memory
     */
    const PARAM_MODE_POSITION = 0;

    /**
     * Parameters are Values
     * "Parameters that an instruction writes to will never be in immediate mode."
     */
    const PARAM_MODE_IMMEDIATE = 1;

    /**
     * Contains opcodes and values
     * @var int[]
     */
    protected $code = [];

    public function setCode(array $intcode)
    {
        $this->code = $intcode;
    }

    /**
     * Current Postion in the code
     * @var int
     */
    protected $p = 0;

    protected $input = [];

    /**
     * Add input from outside
     * @param int $value
     */
    public function addInput(int $value)
    {
        $this->input[] = $value;
    }

    /**
     * Get the first int from input
     * @return int
     */
    protected function getInput()
    {
        return array_shift($this->input);
    }

    protected $output = [];

    /**
     * Make output availabe to outside world
     * @param int $value
     */
    protected function addOutPut(int $value)
    {
        $this->output[] = $value;
    }

    /**
     * @return int[]
     */
    public function getOutput()
    {
        return $this->output;
    }

    public function getResult()
    {
        return $this->code[0];
    }

    public function runProgram()
    {
        while (true) {
            $opcodeAndModes = $this->interpretOpCode();
            $opcode = $opcodeAndModes[0][0];
            $modes = $opcodeAndModes[1];
            $skipInstructions = $this->opcodeInstructions[$opcode];
            $params = $this->getInstructionParams($skipInstructions - 1);

            switch ($opcode) {
                case self::OPCODE_ADD:
                    $p1 = $modes[0] ? $params[0] : $this->code[$params[0]];
                    $p2 = $modes[1] ? $params[1] : $this->code[$params[1]];
                    $this->code[$params[2]] = $p1 + $p2;
                    break;
                case self::OPCODE_MULTIPLY:
                    $p1 = $modes[0] ? $params[0] : $this->code[$params[0]];
                    $p2 = $modes[1] ? $params[1] : $this->code[$params[1]];
                    $this->code[$params[2]] = $p1 * $p2;
                    break;
                case self::OPCODE_INPUT:
                    $this->code[$params[0]] = $this->getInput();
                    break;
                case self::OPCODE_OUTPUT:
                    $p = $modes[0] ? $params[0] : $this->code[$params[0]];
                    $this->addOutPut($p);
                    break;
                case self::OPCODE_HALT:
                    return;
            }
            $this->p += $skipInstructions;
        }
    }

    /**
     *
     * @return array
     */
    protected function interpretOpCode()
    {
        // 1002
        $instruction = $this->code[$this->p];
        // 001002
        $code = str_pad($instruction, 6, '0',STR_PAD_LEFT);
        // 02
        $opcode = (int)substr($code, strlen($code) - 2);
        // 0010 => [0, 0, 1, 0] => [0, 1, 0, 0]
        $codes = array_map(function ($value){return (int)$value; }, array_reverse(str_split(substr($code, 0, -2))));
        // 2, 0, 1, 0, 0
        return [[$opcode], $codes];
    }

    /**
     * Get the Paramters for this instructions
     *
     * @param $count
     * @return array
     */
    protected function getInstructionParams($count)
    {
        $params = [];
        for ($i = 1; $i <= $count; $i++) {
            $value = 0;
            if (isset($this->code[$this->p + $i])) {
                $value = $this->code[$this->p + $i];
            }
            $params[] = $value;
        }
        return $params;
    }
}
