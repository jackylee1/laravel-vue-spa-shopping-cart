export function generatingValidationMessage(type, params = null) {
    let message, param1, param2 = null;

    if (params instanceof Array) {
        if (params.length === 2 && type !== 'enum') {
            param1 = params[0];
            param2 = params[1];
        }
    }
    if (params instanceof String) {
        param1 = params;
    }
    switch (type) {
        case 'required':
            message = 'Это поле обязательно для заполнения';
            break;
        case 'url':
            message = 'Это поле должно быть корректной ссылкой';
            break;
        case 'length':
            if (param2 === null) {
                message = `Значение в этом поле не должно превышать ${param1} символов`;
            }
            else {
                message = `Значение в этом поле должно быть в пределах от ${param2} до ${param1} символов`;
            }
            break;
        case 'email':
            message = 'Это поле должно быть крректным E-mail адресом';
            break;
        case 'array':
            message = 'Это поле должно быть массивом данных';
            break;
        case 'enum':
            let enum_values = params;
            if (params instanceof Array) {
                enum_values = params.join(',');
            }
            message = `В этом поле недопустимое значение. Значение должно быть одним с: ${enum_values}`;
            break;
        case 'integer':
            message = 'Это поле должно быть целочисленным значением';
            break;
        case 'date':
            message = 'Это поле должно быть валидным значением Date (MM.DD.YYYY)';
            break;
    }
    return message;
}