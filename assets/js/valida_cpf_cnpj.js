 function verificarCPF(c) {
        var c = c.replace(/[^\d]+/g, "");
        var i;
        s = c;
        var c = s.substr(0, 9);
        var dv = s.substr(9, 2);
        var d1 = 0;
        var v = false;

        for (i = 0; i < 9; i++) {
            d1 += c.charAt(i) * (10 - i);
        }
        if (d1 == 0) {
            $('#small').html('<small class="text-danger">Esse CPF não e válido</small>');
            $('#cpf').focus();
            v = true;
            return false;
        }
        d1 = 11 - (d1 % 11);
        if (d1 > 9)
            d1 = 0;
        if (dv.charAt(0) != d1) {
            $('#small').html('<small class="text-danger">Esse CPF não e válido</small>');
            $('#cpf').focus();
            v = true;
            return false;
        }

        d1 *= 2;
        for (i = 0; i < 9; i++) {
            d1 += c.charAt(i) * (11 - i);
        }
        d1 = 11 - (d1 % 11);
        if (d1 > 9)
            d1 = 0;
        if (dv.charAt(1) != d1) {
            alert('CPF Invalido');
            v = true;
            return false;
        }
        if (!v) {
            $('#small').html('<small class="text-success">CPF Válido</small>');
        }
    }