#include <ESP8266WiFi.h>
#include <WiFiClient.h> 
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClientSecureBearSSL.h>


const uint8_t trig_pin = D0;
const uint8_t echo_pin = D1;
const byte pino_microfone = D2;
int ledPin = D3; //led do lrd
int ldrPin = A0; //LDR ligado na porta analogica
int ldrValor = 0; //Valor lido do LDR
const byte pino_led = D4;//led do microfone
volatile byte rele = LOW;
uint32_t print_timer;
uint32_t print_timer_microfone;//variavel pra mudar o valor do led do microfone(não deixar receber muitas vezes)
int pino_buzzer = D5;



const char* ssid = "";//nome da rede
const char* password = "";//senha da rede

const char *host = "https://casainteligentesepto.000webhostapp.com/recebe.php?mostrar=1"; 
//const char *host = "http://casainteligente.orgfree.com/recebe.php?mostrar=1";   


void setup() {
  delay(1000);
  Serial.begin(115200);
  WiFi.mode(WIFI_OFF);        
  delay(1000);
  WiFi.mode(WIFI_STA);        
  
  WiFi.begin(ssid, password);     
  Serial.println("");

  Serial.print("Connecting");

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }


  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());  

  pinMode(pino_microfone, INPUT_PULLUP); //Coloca o pino do sensor de som como entrada
  pinMode(pino_led, OUTPUT); 
  pinMode(trig_pin, OUTPUT);
  pinMode(echo_pin, INPUT);
  pinMode(pino_buzzer, OUTPUT);
  pinMode(ledPin,OUTPUT);
  digitalWrite(trig_pin, LOW);
  attachInterrupt(digitalPinToInterrupt(pino_microfone), interrupcaoMicrofone, RISING);//Função para ativar o led quando o microfone receber alguma entrada
}


void loop() {
  std::unique_ptr<BearSSL::WiFiClientSecure>client(new BearSSL::WiFiClientSecure);
  client->setInsecure();
  
  Serial.println("começo");
  HTTPClient http;    

  String Link = host;

  http.begin(*client,Link);    
  http.addHeader("Content-Type", "text/html");
  int httpCode = http.GET();            
  String payload = http.getString();  

  char seguranca = payload[1];
  char ldr = payload[2];
  char luzLdr = payload[3];
  char microfone = payload[4]; 

  if( seguranca == 'L'){
    ultrassom();
  }
  if (ldr == 'L' ){
    resistorLdr();
  }
  if ( luzLdr == 'L' ){
    digitalWrite(ledPin,HIGH);
  }else {
    digitalWrite(ledPin,LOW);
  }
  if ( microfone == 'L' ){
    digitalWrite(pino_led,rele);
  }
  
  Serial.println(httpCode);   
  Serial.println(payload[1]);
  Serial.println(payload[2]);     
  Serial.println(payload[3]);
  Serial.println(payload[4]);
  http.end();  
  
  delay(2000);  //GET Data at every 2 seconds
}

void ativaBuzzer(){
    for (int i = 1; i <= 3; i++)
    {
      digitalWrite(pino_buzzer, HIGH);
      delay(100);
      digitalWrite(pino_buzzer, LOW);
      delay(100);
    }
    // Envia mensagem para o Serial Monitor
    Serial.println("Movimento detectado!");
    delay(100); 
          
}

void ultrassom(){
   if (millis() - print_timer > 500) {
          print_timer = millis();
           
          // Pulso de 5V por pelo menos 10us para iniciar medição.
          digitalWrite(trig_pin, HIGH);
          delayMicroseconds(11);
          digitalWrite(trig_pin, LOW);

          uint32_t pulse_time = pulseIn(echo_pin, HIGH);
          
          double distance = 0.01715 * pulse_time;
           
          // Imprimimos o valor na porta serial;
          Serial.print(distance);
          Serial.println(" cm");

          if( distance<19){//função que ativa o alarme quando algum movimento é detectado
                ativaBuzzer();            
          }          
    }
}

void resistorLdr(){

 ldrValor = analogRead(ldrPin); //O valor lido será entre 0 e 1023
 
 if (ldrValor>= 600){ 
    digitalWrite(ledPin,HIGH);
    }

 else {digitalWrite(ledPin,LOW);}
        
}
ICACHE_RAM_ATTR void interrupcaoMicrofone(){
  if (millis() - print_timer_microfone > 1000) {    
     print_timer_microfone = millis();           
     rele = !rele; //Operacao NAO: Se estiver LOW, passa pra HIGH. Se estiver HIGH passa para LOW
     Serial.println("RECEBEU SOM");
    }   
}
