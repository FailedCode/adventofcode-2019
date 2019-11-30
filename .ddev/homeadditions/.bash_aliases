
function prompt {
  local GREEN="\[\e[0;32m\]"
  local RED="\[\e[0;31m\]"
  local CYAN="\[\e[0;36m\]"
  local BLUE="\[\e[0;94m\]"
  local DARK_BLUE="\[\e[0;34m\]"
  local YELLOW="\[\e[0;33m\]"
  local NO_COLOR="\[\e[0;0m\]"


  PS1="\n${GREEN}[${YELLOW}DDEV${GREEN}]${CYAN}\u@${CYAN}\H:${BLUE}\w ${GREEN}[\t]\n${RED}\$ ${NO_COLOR}"
  export PS1
}

prompt


export LS_OPTIONS='--color=auto'
alias ls='ls $LS_OPTIONS'
alias ll='ls $LS_OPTIONS -lhA'
alias l='ls $LS_OPTIONS -lh'

alias disable_xdebug="phpdismod xdebug && killall -1 php-fpm"
alias enable_xdebug="phpenmod xdebug && killall -1 php-fpm"

alias grep="grep --color"
alias mkdir="mkdir -pv"
