import { ChangeEvent, FC, useEffect } from 'react';
import { Form, Input } from 'antd';
import { IQuestion, QuestionFormStore } from './QuestionFormStore';
import AnswerForm from './AnswerForm';
import { observer } from 'mobx-react-lite';

interface QuestionFormItemProps {
  qKey?: number;
  question?: IQuestion;
  inputChange?: QuestionFormStore['inputChange'];
}

const QuestionFormItem: FC<QuestionFormItemProps> = observer(({ qKey, question, inputChange }) => {
  useEffect(() => {
    inputChange(qKey, qKey + 1, 'position');
  }, []);

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    inputChange(qKey, e.target.value, e.target.name);
  };

  return (
    <div>
      <Form layout="vertical">
        <Form.Item label="Текст вопроса">
          <Input
            name="questionText"
            placeholder="Введите текст вопроса"
            value={question.questionText}
            onChange={handleChange}
          />
        </Form.Item>
        <AnswerForm qKey={qKey} />
        <Form.Item label="Число баллов за правильный ответ">
          <Input
            className="width-100"
            name="points"
            type="number"
            value={question.points}
            placeholder="Введите число баллов за правильный ответ"
            onChange={handleChange}
          />
        </Form.Item>
      </Form>
    </div>
  );
});

export default QuestionFormItem;
