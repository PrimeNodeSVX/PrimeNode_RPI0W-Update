###############################################################################
#
# Parrot module event handlers - WERSJA UNIWERSALNA (FIX ARGUMENTS)
#
###############################################################################

namespace eval Parrot {

# Zaladowanie podstawy modulu
sourceTclWithOverrides "Module.tcl"
mixin Module

# --- NAPRAWA BLĘDÓW LOGA ---

# Fix dla bledu: wrong # args
# Uzycie {args} sprawia, ze funkcja lyknie dowolna ilosc parametrow i nie wywali bledu
proc squelchOpen {args} {
  return 0
}

# Fix dla bledu: invalid command name
proc allMsgsWritten {args} {
  return 0
}

# Przekierowanie starej nazwy na nowa (dla kompatybilnosci)
proc activateInit {} {
  activating_module
}

# --- RESZTA FUNKCJI ---

proc spell_digits {digits} {
  spellWord $digits;
  playSilence 500;
}

proc all_played {} {
}

}
