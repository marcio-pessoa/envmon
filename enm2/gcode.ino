/* gcode.ino, envmon Mark II - Environment Monitor, G-code parser sketch file
 * 
 * Author: Márcio Pessoa <marcio@pessoa.eti.br>
 * Contributors: none
 */

#define BUFFER_SIZE 48

char buffer[BUFFER_SIZE];
int buffer_pointer = 0;

bool echo(String message) {
  Console.print(String("echo:") + message);
  Console.print('\0');
}

bool echoln(String message) {
  echo(message + "\n");
}

void status(bool i) {
  echoln(i == false ? "ok" : "nok");
}

void GcodeReady() {
  buffer_pointer = 0;
}

void GcodeCheck() {
  while (Console.available() > 0) {
    char c = Console.read();
    if (buffer_pointer < BUFFER_SIZE-1) {
      buffer[buffer_pointer++] = c;
    }
    if (c == '\n') {
      buffer[buffer_pointer] = 0;
      GcodeParse();
      GcodeReady();
    }
  }
}

float GcodeNumber(char code, float val) {
  char *ptr = buffer;
  while(ptr && *ptr && ptr < buffer + buffer_pointer) {
    if(*ptr == code) {
      return atof(ptr + 1);
    }
    ptr = strchr(ptr, ' ') + 1;
  }
  return val;
}

void GcodeParse() {
  bool retval = false;
  char letter = buffer[0];
  byte number = GcodeNumber(letter, -1);
  switch (letter) {
    case 'M': switch(number) {
        case 0:
          CommandM100(letter);
          break;
        case 2:
          CommandM2();
          break;
        case 10:
          retval = CommandM10(GcodeNumber('F', 0),
                              GcodeNumber('D', 0));
          break;
        case 20:
          retval = CommandM20(GcodeNumber('R', 0),
                              GcodeNumber('G', 0),
                              GcodeNumber('B', 0));
          break;
        case 30:
          retval = CommandM30();
          break;
        case 40:
          retval = CommandM40(1);
          break;
        case 50:
          retval = CommandM50(1);
          break;
        case 60:
          retval = CommandM60(GcodeNumber('S', 0));
          break;
        case 70:
          retval = CommandM70(1);
          break;
        case 80:
          retval = CommandM80(1);
          break;
        case 81:
          retval = CommandM81(1);
          break;
        case 82:
          retval = CommandM82(1);
          break;
        case 90:
          CommandM90();
          break;
        case 91:
          CommandM91();
          break;
        case 99:
          CommandM99();
          break;
        case 100:
          CommandM100(letter);
          break;
        default:
          Command0();
          break;
      }
      break;
    default:
      if (buffer_pointer > 2) {
        Command0();
      }
      break;
  }
  if (buffer_pointer > 2) {
    status(retval);
  }
}
