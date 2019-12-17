<?php


namespace App\Service;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class IntcodeComputer
{
    const OPCODE_ADD = 1;
    const OPCODE_MULTIPLY = 2;
    const OPCODE_INPUT = 3;
    const OPCODE_OUTPUT = 4;
    const OPCODE_JMP_IF_TRUE = 5;
    const OPCODE_JMP_IF_FALSE = 6;
    const OPCODE_LESSTHAN = 7;
    const OPCODE_EQUALS = 8;
    const OPCODE_RELATIVE_BASE_OFFSET = 9;
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
        self::OPCODE_JMP_IF_TRUE => 3,
        self::OPCODE_JMP_IF_FALSE => 3,
        self::OPCODE_LESSTHAN => 4,
        self::OPCODE_EQUALS => 4,
        self::OPCODE_RELATIVE_BASE_OFFSET => 2,
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
     * the parameter is interpreted as a position
     * Like position mode, parameters in relative mode can be read from or written to.
     */
    const PARAM_MODE_RELATIVE = 2;

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

    /**
     * Used with Parameter mode relative
     * @var int
     */
    protected $relativeBase = 0;

    protected $input = [];

    /**
     * Used for debugging
     * @var int
     */
    protected $instructionCounter = 0;

    /**
     * @var bool
     */
    protected $hasHaltet = false;

    /**
     * @return bool
     */
    public function hasHaltet()
    {
        return $this->hasHaltet;
    }

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

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->logger->info("Set new Logger!");
    }

    /**
     * @param bool $pauseOnOutput
     * @return int|void
     * @throws \Exception
     */
    public function runProgram($pauseOnOutput = false)
    {
        $this->logger->info("runProgram");
        while (true) {
            $this->instructionCounter += 1;
            $opcodeAndModes = $this->interpretOpCode();
            $opcode = $opcodeAndModes[0][0];
            $modes = $opcodeAndModes[1];
            $skipInstructions = $this->opcodeInstructions[$opcode] ?? 1;
            $params = $this->getInstructionParams($skipInstructions - 1);

            switch ($opcode) {
                case self::OPCODE_ADD:
                    $this->logger->info("ADD");
                    $p1 = $this->getParam(0, $modes, $params);
                    $p2 = $this->getParam(1, $modes, $params);
                    $p3 = $this->getParamWrite(2, $modes, $params);
                    $this->code[$p3] = $p1 + $p2;
                    break;
                case self::OPCODE_MULTIPLY:
                    $this->logger->info("MULTIPLY");
                    $p1 = $this->getParam(0, $modes, $params);
                    $p2 = $this->getParam(1, $modes, $params);
                    $p3 = $this->getParamWrite(2, $modes, $params);
                    $this->code[$p3] = $p1 * $p2;
                    break;
                case self::OPCODE_INPUT:
                    $this->logger->info("INPUT");
                    $this->code[$this->getParamWrite(0, $modes, $params)] = $this->getInput();
                    break;
                case self::OPCODE_OUTPUT:
                    $this->logger->info("OUTPUT");
                    $p = $this->getParam(0, $modes, $params);
                    $this->addOutPut($p);
                    if ($pauseOnOutput) {
                        $this->p += $skipInstructions;
                        return $p;
                    }
                    break;
                case self::OPCODE_JMP_IF_TRUE:
                    $this->logger->info("JMP IF TRUE");
                    $p1 = $this->getParam(0, $modes, $params);
                    $p2 = $this->getParam(1, $modes, $params);
                    if ($p1 != 0) {
                        $this->p = $p2;
                        continue 2;
                    }
                    break;
                case self::OPCODE_JMP_IF_FALSE:
                    $this->logger->info("JMP IF FALSE");
                    $p1 = $this->getParam(0, $modes, $params);
                    $p2 = $this->getParam(1, $modes, $params);
                    if ($p1 == 0) {
                        $this->p = $p2;
                        continue 2;
                    }
                    break;
                case self::OPCODE_LESSTHAN:
                    $this->logger->info("LESS THAN");
                    $p1 = $this->getParam(0, $modes, $params);
                    $p2 = $this->getParam(1, $modes, $params);
                    $this->code[$this->getParamWrite(2, $modes, $params)] = ($p1 < $p2) ? 1 : 0;
                    break;
                case self::OPCODE_EQUALS:
                    $this->logger->info("EQUALS");
                    $p1 = $this->getParam(0, $modes, $params);
                    $p2 = $this->getParam(1, $modes, $params);
                    $this->code[$this->getParamWrite(2, $modes, $params)] = ($p1 == $p2) ? 1 : 0;
                    break;
                case self::OPCODE_RELATIVE_BASE_OFFSET:
                    $this->logger->info("RELATIVE BASE OFFSET");
                    $this->relativeBase += $this->getParam(0, $modes, $params);
                    break;
                case self::OPCODE_HALT:
                    $this->logger->info("HALT");
                    $this->hasHaltet = true;
                    return;
                default:
                    throw new \Exception("OpCode $opcode not yet implemented!");
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

    /**
     * @param int $n
     * @param array $modes
     * @param array $params
     * @return int
     */
    protected function getParam($n, $modes, $params)
    {
        $result = null;
        switch ($modes[$n]) {
            case self::PARAM_MODE_POSITION:
                $result = $this->code[$params[$n]] ?? 0;
                break;
            case self::PARAM_MODE_IMMEDIATE:
                $result = $params[$n];
                break;
            case self::PARAM_MODE_RELATIVE:
                $result = $this->code[$this->relativeBase + $params[$n]] ?? 0;
                break;
        }

        $this->logger->info("ParamNR {$n}, mode {$modes[$n]}, Param {$params[$n]} => {$result}");
        return $result;
    }

    /**
     * @param int $n
     * @param array $modes
     * @param array $params
     * @return int
     */
    protected function getParamWrite($n, $modes, $params)
    {
        $result = null;
        switch ($modes[$n]) {
            case self::PARAM_MODE_RELATIVE:
                $result = $this->relativeBase + $params[$n];
                break;
            default:
                $result = $params[$n];
                break;
        }

        $this->logger->info("ParamNR {$n}, mode {$modes[$n]}, Param {$params[$n]} => {$result}");
        return $result;
    }
}
