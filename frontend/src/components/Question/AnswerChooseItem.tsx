import React, {FC, useState} from 'react';
import {Col, Form, FormGroup, Row} from "react-bootstrap";
import {IAnswer} from "../../types/types";

interface AnswerChooseItemProps {
    answer: IAnswer;
}

const AnswerChooseItem: FC<AnswerChooseItemProps> = ({answer}) => {
    const [answerText, setAnswerText] = useState<string>('');
    const [correctAnswers, setCorrectAnswers] = useState<[]>([]);

    const changeAnswerText = (e: React.ChangeEvent<HTMLInputElement>) => {
        setAnswerText(e.target.value)
    }
    let checked = false

    return (
        <div>
            <Form>
                <Row className="align-items-center">
                    <Col xs="auto">
                        <Form.Control
                            placeholder="Текст ответа"
                            onChange={changeAnswerText}
                        />
                    </Col>

                    <Col xs="auto">
                        <Form.Check
                            type="checkbox"
                            checked={checked}
                            className="mb-2"
                            label="Правильный ответ"
                        />
                    </Col>

                </Row>
            </Form>

        </div>
    );
};

export default AnswerChooseItem;
