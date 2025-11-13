# Bcrypt Hash Oluşturma Rehberi

## 4 Admin Kullanıcı için Şifre Hash'leri

### Online Tool Kullanarak (En Kolay)

1. **https://bcrypt-generator.com/** adresine gidin
2. Her şifre için hash oluşturun:

#### 1. Eda Meric Sefer
- **Username:** `eda.meric`
- **Password:** `Frankfurt2025!`
- **Rounds:** 10
- Oluşturulan hash'i kopyalayın

#### 2. Ozkan Cengiz
- **Username:** `ozkan.cengiz`
- **Password:** `Munich2025!`
- **Rounds:** 10
- Oluşturulan hash'i kopyalayın

#### 3. Mehmet Ali Sariad
- **Username:** `mehmet.sariad`
- **Password:** `Nuremberg2025!`
- **Rounds:** 10
- Oluşturulan hash'i kopyalayın

#### 4. Mert Cengiz
- **Username:** `mert.cengiz`
- **Password:** `Berlin2025!`
- **Rounds:** 10
- Oluşturulan hash'i kopyalayın

### Node.js ile (Geliştiriciler için)

```bash
# 1. Bağımlılıkları yükleyin
npm install

# 2. Hash'leri oluşturun
node generate-password-hashes.js
```

Bu script her kullanıcı için:
- Bcrypt hash oluşturur
- SQL INSERT statement yazdırır
- Hash'leri kopyalayıp kullanabilirsiniz

### Python ile (Alternatif)

```python
import bcrypt

passwords = {
    'eda.meric': 'Frankfurt2025!',
    'ozkan.cengiz': 'Munich2025!',
    'mehmet.sariad': 'Nuremberg2025!',
    'mert.cengiz': 'Berlin2025!'
}

for username, password in passwords.items():
    hash = bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt(rounds=10))
    print(f"{username}: {hash.decode('utf-8')}")
```

## Supabase'de Kullanım

1. `SUPABASE_READY.sql` dosyasını açın
2. Her `$2b$10$YOUR_BCRYPT_HASH_HERE` yerine oluşturduğunuz hash'leri yapıştırın
3. Supabase SQL Editor'de çalıştırın

## Örnek Hash Formatı

Bcrypt hash'leri şu formatta olmalıdır:
```
$2b$10$XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
```

Örnek:
```
$2b$10$rK9Q8XJ7Z5YxVnM2PqL3.eO8K9J7Z5YxVnM2PqL3.eO8K9J7Z5YxV
```

## Notlar

- **Cost Factor (Rounds):** 10 kullanın (güvenlik ve performans dengesi)
- **Hash Uzunluğu:** Bcrypt hash'leri 60 karakter uzunluğundadır
- **Güvenlik:** Hash'leri asla paylaşmayın veya versiyon kontrolüne eklemeyin

## Hızlı Test

Hash'lerin doğru olduğunu test etmek için:

```javascript
const bcrypt = require('bcrypt');
const password = 'Frankfurt2025!';
const hash = '$2b$10$YOUR_HASH_HERE';

const isValid = bcrypt.compareSync(password, hash);
console.log('Password valid:', isValid);
```







