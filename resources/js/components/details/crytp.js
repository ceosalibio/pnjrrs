import CryptoJS from "crypto-js";

export function decryptBase(val){
    let data = atob(val)
    return JSON.parse(data)
}

const SECRET_KEY = 'dPHvFpkJGTLXBwSKlqh8QxCLH29ZR3b4';

export function encryptData(data){
    return CryptoJS.AES.encrypt(JSON.stringify(data),SECRET_KEY).toString();
}



export function decryptData(data){
    const bytes = CryptoJS.AES.decrypt(data,SECRET_KEY)
    const decryptData = bytes.toString(CryptoJS.enc.Utf8)

    return JSON.parse(decryptData)
}







export function setItem(key, value) {
    const encryptedData = encryptData(value);
    sessionStorage.setItem(key, encryptedData);
  }
  
  export function getItem(key) {
    const encryptedData = sessionStorage.getItem(key);
    if (!encryptedData) return null;
  
    try {
      return decryptData(encryptedData);
    } catch (e) {
      console.error('Error decrypting data', e);
      return null;
    }
  }
  
  export function removeItem(key) {
    sessionStorage.removeItem(key);
  }

  // decryption data PHP encrypted data

  function aesEvpKDF(password, salt, keySize = 8, ivSize = 4, iterations = 1, hashAlgorithm = "md5") {
    const targetKeySize = keySize + ivSize;
    let derivedBytes = "";
    let numberOfDerivedWords = 0;
    let block = null;
    const hasher = CryptoJS.algo.MD5.create();
    while (numberOfDerivedWords < targetKeySize) {
      if (block !== null) {
        hasher.update(block);
      }
      hasher.update(password);
      hasher.update(salt);
      block = hasher.finalize();
      hasher.reset();
  
      // Iterations
      for (let i = 1; i < iterations; i++) {
        hasher.update(block);
        block = hasher.finalize();
        hasher.reset();
      }
  
      derivedBytes += CryptoJS.enc.Hex.stringify(block).slice(0, Math.min(block.sigBytes, (targetKeySize - numberOfDerivedWords) * 4));
  
      numberOfDerivedWords += block.sigBytes / 4;
    }
    return {
      key: CryptoJS.enc.Hex.parse(derivedBytes.slice(0, keySize * 4)),
      iv: CryptoJS.enc.Hex.parse(derivedBytes.slice(keySize * 4, (keySize + ivSize) * 4))
    };
  }
  
  export function decryptCryptoPHP(encryptedData) {
    const encryptedBytes = CryptoJS.enc.Base64.parse(encryptedData);
    const encryptedBytesString = encryptedBytes.toString(CryptoJS.enc.Hex);
    const saltBytes = encryptedBytesString.slice(0, 16);
    const encryptedBytesWithoutSalt = encryptedBytesString.slice(16);
  
    const key = SECRET_KEY; // you'll need to implement this method to return the crypto key
    const keyAndIV = aesEvpKDF(key, saltBytes);
  
    const decryptedBytes = CryptoJS.AES.decrypt(
      encryptedBytesWithoutSalt,
      keyAndIV.key,
      {
        iv: keyAndIV.iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
      }
    );
  
    const decryptedData = decryptedBytes.toString(CryptoJS.enc.Utf8);
    return JSON.parse(decryptedData);
  }

  