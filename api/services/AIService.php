<?php
error_reporting(0);
ini_set('display_errors', 0);
class AIService {
    private $apiKey;
    private $models = ['gemini-2.0-flash-lite', 'gemini-2.5-flash-lite', 'gemini-2.0-flash', 'gemini-2.5-flash'];
    private $currentModelIndex = 0;
    
    public function __construct() {
        $this->apiKey = 'AIzaSyCQAaY6jJRGUhsSxxYzI0t0tU4w1gMZr0Q';
    }
    
    public function parseNutritionInput($userInput) {
        if (empty($this->apiKey)) {
            return ['success' => false, 'error' => 'API key not configured'];
        }
        
        $prompt = "You are a nutrition expert. Parse natural language food and exercise input.

FOOD RULES:
- Recognize ALL global foods (American, Indian, Chinese, Italian, Mexican, Japanese, Middle Eastern, etc.)
- Use standard portions: 1 egg, 1 slice, 1 cup, 100g, 1 serving, 1 piece, 1 bowl
- If NO quantity mentioned (just 'chicken' or 'rice'), return error: {\"error\": \"Please specify quantity (e.g., 2 eggs, 100g chicken, 1 bowl rice)\"}
- For complex dishes, break into ingredients with separate entries

GLOBAL FOOD DATABASE (per 100g unless specified):
Proteins: chicken 165kcal (31p/0c/3.6f), beef 250kcal (26p/0c/17f), fish 206kcal (22p/0c/12f), egg (1pc) 70kcal (6p/0c/5f), paneer 265kcal (18p/1c/20f), tofu 76kcal (8p/2c/4.8f)
Grains: rice 130kcal (3p/28c/0f), bread (1 slice) 80kcal (3p/15c/1f), pasta 131kcal (5p/25c/1f), roti (1pc) 120kcal (3p/18c/3f), noodles 138kcal (5p/25c/2f)
Vegetables: broccoli 34kcal (3p/7c/0f), spinach 23kcal (3p/4c/0f), potato 77kcal (2p/17c/0f), tomato 18kcal (1p/4c/0f)
Fruits: banana (1pc) 105kcal (1p/27c/0f), apple (1pc) 95kcal (0p/25c/0f), orange (1pc) 62kcal (1p/15c/0f)
Dairy: milk (1 cup) 149kcal (8p/12c/8f), yogurt 59kcal (10p/4c/0f), cheese 402kcal (25p/1c/33f)
Fats: butter 717kcal (1p/0c/81f), oil 884kcal (0p/0c/100f), ghee 900kcal (0p/0c/100f)

ACTIVITY RULES:
- Recognize ALL activities: cardio (running, cycling, swimming), strength (pushups, situps, squats, pullups), sports (basketball, soccer, tennis), gym exercises
- For TIME-BASED: running 30min, cycling 45min, swimming 20min → {\"name\": \"running\", \"duration\": 30, \"est_calories\": 300}
- For REP-BASED: 30 pushups, 50 situps, 20 squats → {\"name\": \"pushups\", \"reps\": 30, \"est_calories\": 15} (0.5 kcal per rep)
- Calorie estimates: pushups/situps/squats 0.5kcal/rep, pullups 1kcal/rep, running 10kcal/min, cycling 8kcal/min, swimming 12kcal/min, gym 7kcal/min

RETURN FORMAT:
{\"foods\": [{\"name\": \"chicken\", \"quantity\": 100, \"est_calories\": 165, \"est_protein\": 31, \"est_carbs\": 0, \"est_fat\": 3.6}], \"activities\": [{\"name\": \"pushups\", \"reps\": 30, \"est_calories\": 15}]}

For complex dishes, return MULTIPLE food items. Example: 'butter chicken' → [{chicken 100g}, {butter 10g}, {curry gravy 50g}]

User input: " . $userInput;
        
        // Try each model until one works
        foreach ($this->models as $model) {
            $result = $this->callGeminiAPI($model, $prompt);
            
            // If successful, return immediately
            if ($result['success']) {
                return $result;
            }
            
            // If quota exceeded (429), try next model
            if (isset($result['http_code']) && $result['http_code'] === 429) {
                continue;
            }
            
            // For other errors, return immediately
            return $result;
        }
        
        // All models failed
        return [
            'success' => false, 
            'error' => 'All AI models quota exceeded. Please try again later or use a different API key.',
            'user_message' => '⏳ AI quota limit reached. Please try again in a few hours.'
        ];
    }
    
    private function callGeminiAPI($model, $prompt) {
        $url = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$this->apiKey}";
        
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            return ['success' => false, 'error' => 'cURL error: ' . $curlError];
        }
        
        if ($httpCode !== 200) {
            return ['success' => false, 'error' => 'API request failed', 'http_code' => $httpCode, 'response' => $response, 'model' => $model];
        }
        
        $result = json_decode($response, true);
        
        if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return ['success' => false, 'error' => 'Invalid API response', 'response' => $result];
        }
        
        $aiText = $result['candidates'][0]['content']['parts'][0]['text'];
        
        // Extract JSON from response
        if (preg_match('/\{[\s\S]*\}/', $aiText, $matches)) {
            $parsed = json_decode($matches[0], true);
            if ($parsed) {
                return ['success' => true, 'data' => $parsed, 'model_used' => $model];
            }
        }
        
        return ['success' => false, 'error' => 'Could not parse AI response', 'ai_text' => $aiText];
    }
}
